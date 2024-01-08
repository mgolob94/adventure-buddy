<?php

class RM_Front_Form_Controller_Addon
{

    //New form handling
    public function process($model, $service, $request, $params, $parent_controller)
    {
        if ($service->is_ip_banned())
        {
            return $parent_controller->banned_view();
        }
        
        global $rm_form_diary;
        $user_id= null;
        if(count($rm_form_diary)>0 && !isset($params['force_enable_multiform']))
            return;

        if (isset($params['form_id']) && $params['form_id'])
        {
            $form_id = $params['form_id'];
            $fe_form = $parent_controller->form_factory->create_form($form_id);
            $form_name = 'form_' . $fe_form->get_form_id();
        } else
        {
            return;
        }             
        
        $fopts = $fe_form->get_form_options();
        $total_price = $fe_form->get_pricing_detail($request->req);
        
        if($fe_form->is_expired() && $fopts->post_expiry_action == 'switch_to_another_form')
        {
            $form_id = $fopts->post_expiry_form_id;
            if ($form_id)
            {
                $fe_form = $parent_controller->form_factory->create_form($form_id);
                $form_name = 'form_' . $form_id;
                $params['form_id'] = $form_id;
            } else
            {
                return;
            }
        }
        
        //Form access interception
        $fac_responce = $parent_controller->test_form_access_v2($fe_form, $service, $request->req, $params);
        
        if ($fac_responce->status != 'allowed')
            return $fac_responce->html_str;
        ///////////////////////////////////

        if (isset($request->req['rm_pproc'], $request->req['rm_fid'], $request->req['rm_fno'], $request->req['sh'], $rm_form_diary[$form_id])
                && $request->req['rm_fid'] == $form_id && $request->req['rm_fno'] == $rm_form_diary[$form_id])
        {
           
            $paypal_service = new RM_Paypal_Service();
            ob_start();
            $resp = $paypal_service->callback($request->req['rm_pproc'], isset($request->req['rm_pproc_id']) ? $request->req['rm_pproc_id'] : null, $request->req['sh']);
            $paypal_callback_msg = ob_get_clean();
            $x = new stdClass;
            $x->form_options = $fe_form->get_form_options();
            $x->form_name = $fe_form->get_form_name();
            $x->sub_id = absint($request->req['rm_subid']);
            $after_sub_msg = $service->after_submission_proc($x);
            echo  $paypal_callback_msg . '<br><br>' . $after_sub_msg;
            return;
            //die;
            //echo  $paypal_callback_msg . '<br><br>' . $after_sub_msg.$request->req['token'];//die;
        }
        
        if (isset($request->req['payment_intent_client_secret'])) {
            echo RM_Utilities::enqueue_external_scripts('stripe_script', esc_url_raw('https://js.stripe.com/v3/'), array());
            echo RM_Utilities::enqueue_external_scripts('stripe_utility_script',RM_ADDON_BASE_URL. 'public/js/stripe_checkout_utility.js', array());
            $rm_admin_vars = array('nonce'=>wp_create_nonce('rm_ajax_secure'));
            wp_localize_script('stripe_utility_script','rm_admin_vars',$rm_admin_vars);
            wp_enqueue_style('rm_stripe_checkout_style');
            
            $log_entry_id = $request->req['log_id'];
            $total_price = $request->req['total_price'];
            $submission_id = $request->req['sub_id'];
            $description = $request->req['description'];
            $label = __('Please enter the details to complete the payment:','registrationmagic-addon');
            $btn_label = __('Pay','registrationmagic-addon').' '.RM_Utilities::get_formatted_price($total_price);
            echo "<div class=\"rm_stripe_fields\">
                    <div class=\"rm_stripe_label\" style=\"display:none\">$label</div>
                    <form id=\"rm-stripe-payment-form\" data-log-id=\"$log_entry_id\" data-total-price=\"$total_price\" data-submission-id=\"$submission_id\" data-description=\"$description\">
                      <div id=\"rm-stripe-payment-element\" style=\"display:none\">
                        <!--Stripe.js injects the Payment Element-->
                      </div>
                      <button id=\"rm-stripe-submit\" style=\"display:none\">
                        <div class=\"rm-stripe-spinner rm-stripe-hidden\" id=\"rm-stripe-spinner\"></div>
                        <span id=\"rm-stripe-button-text\">$btn_label</span>
                      </button>
                      <div id=\"rm-stripe-payment-message\" class=\"rm-stripe-hidden\"></div>
                    </form>
                </div>";
            return;
        }
        
        do_action('rm_pre_form_proc',$request->req);
        
        if ($service->is_off_limit_submission($form_id, $fopts))
        {
            return RM_UI_Strings::get("ALERT_SUBMISSIOM_LIMIT");
        }
        
        //Call form specific processing before submission.
        $form_preproc_response = $fe_form->pre_sub_proc($request->req, $params);        
        if (isset($request->req['stat_id']))
            $stat_id = $request->req['stat_id'];
        else
            $stat_id = null;
        
        if (isset($request->req['rm_form_sub_no']) && $request->req['rm_form_sub_no'])
            $subbed_form_no = $request->req['rm_form_sub_no'];
        else
            $subbed_form_no = null;
        
        $form_object_for_test = $fe_form->get_form_object();
        if(!empty($subbed_form_no)){
            // Check for submission limit by custom status
            $primary_data = $fe_form->get_prepared_data($request->req, 'primary');
            if(isset($primary_data['user_email'])){
                $email= $primary_data['user_email']->value;
                if(!RM_DBManager::is_allowed_by_custom_status($email,$form_id)){
                    return RM_UI_Strings::get("ALERT_SUBMISSIOM_LIMIT_BY_STATUS");
                }
            }  
        }


        if ($subbed_form_no && ($fe_form->get_form_number() == $subbed_form_no) && $form_preproc_response && $parent_controller->mv_handler->validateForm($form_name."_".$subbed_form_no, $form_object_for_test) /*&& !$service->is_browser_reload_duplication($stat_id)*/)
        {
            $primary_data = $fe_form->get_prepared_data($request->req, 'primary');
            
            if ($service->is_email_banned($primary_data['user_email']->value))
            {
                $service->update_stat_entry($stat_id, 'ban');
                return $parent_controller->banned_view();
            }
            
            $db_data = $fe_form->get_prepared_data($request->req, 'dbonly');
            $form_options = $fe_form->get_form_options();
            $factrl = $form_options->access_control;
            if(!empty($factrl->domain)){
                $domains = explode(',', $factrl->domain);
                if(!empty($primary_data['user_email'])){
                    $parts = explode('@',$primary_data['user_email']->value); // Separate string by @ characters (there should be only one)
                    $domain = array_pop($parts); // Remove and return the last part, which should be the domain
                    // Check if the domain is in our list
                    if (!in_array($domain, $domains))
                    {
                        echo $factrl->fail_msg;
                        return;
                    }
                }
            }
            
            $sub_detail = $service->save_submission($form_id, $db_data, $primary_data['user_email']->value);
            if(isset($sub_detail))
                $service->update_stat_entry($stat_id,'update',$sub_detail->submission_id);
            
            if ((int) ($form_options->should_export_submissions) === 1) {
                $service->export_to_external_url($form_options->export_submissions_to_url, $db_data);
            }
            
            // Getting $db_data again to maintain array structure for Address field
            $db_data = $fe_form->get_prepared_data($request->req, 'dbonly');
            
            if ($form_options->form_is_unique_token)
                $token = $sub_detail->token;
            else
                $token = null;
            $request->req['token']=$token;
            
            $submission = new RM_Submissions;
            $submission->load_from_db($sub_detail->submission_id);
            
            if ($form_options->form_should_send_email)
            {
                $parameters = new stdClass; //This is different then the $params in the argument of this function!
                $parameters->req = $request->req;
                $parameters->db_data = $db_data;
                $parameters->email = $primary_data['user_email']->value;
                $parameters->email_content = $form_options->form_email_content;
                $parameters->email_subject = $form_options->form_email_subject;
                $parameters->total_price = empty($total_price) ? 0 : $total_price;
                $parameters->sub_id = $sub_detail->submission_id;
                $parameters->form_id = $form_id;
                $parameters->sub_data = $submission->get_data();
               // $email = $service->prepare_email('to_registrar', $token, $parameters);
                RM_Email_Service::auto_responder($parameters,$token);
                //RM_Utilities::send_mail($email);
            }
            $should_attach_sub_pdf = $service->get_setting('admin_notification_includes_pdf');
            
            $sub_pdf_loc = null;
            if($should_attach_sub_pdf == 'yes' || $form_options->enable_dpx=="1")
            {
                //Address for submission pdf to create temporarily
                $sub_pdf_loc = get_temp_dir().'rm_submission_'.$submission->get_submission_id().'.pdf';
                //Ouput the pdf to desired location
                $service->output_pdf_for_submission($submission,array('name' => $sub_pdf_loc, 'type' => 'F'));  
            }
            
            $parameters = new stdClass;
            $parameters->sub_data = $submission->get_data();
            $parameters->form_name = $fe_form->get_form_name();
            //Attachments for the mail
            $parameters->attachment = $should_attach_sub_pdf == 'yes'? $sub_pdf_loc : null;
            //Changing attachment URL for PDF merging
            if(!empty($parameters->attachment))
                $parameters->attachment = apply_filters('rm_admin_notification_attachment_merger', $parameters->attachment, $submission->get_submission_id(), $form_id);
            $parameters->sub_id = $sub_detail->submission_id;
            $parameters->form_id = $form_id;
            
            //wp_schedule_single_event( time() + 2, 'rm_after_submission',array($sub_detail,$request->req,$sub_pdf_loc));
            do_action('rm_after_submission',$sub_detail,$request->req,$sub_pdf_loc);
            
            $params['sub_detail'] = $sub_detail;

            /*
             * Check for payment
             */
            //also call Form specific method after submission
            $prevent_redirection = false;
            $redirection_html='';
            if ($fe_form->has_price_field() && $service->get_setting('payment_gateway'))
            {
                $params['paystate'] = 'pre_payment';
                $params['user_email'] = $primary_data['user_email']->value;
                $user_id= $fe_form->post_sub_proc($request->req, $params, false);               
                $params['is_paid'] = $service->process_payment($fe_form, $request, $params);
                
                if(isset($params['is_paid']['html']))
                    $redirection_html=$params['is_paid']['html'];
                
               if (isset($params['is_paid']['status']) && $params['is_paid']['status'] === 'do_not_redirect') {
                    $params['paystate'] = 'post_payment';
                    $params['is_paid'] = false; //Set that so "true" checks for is_paid do not fail because of "do_not_redirect".
                    $user_id= $fe_form->post_sub_proc($request->req, $params, false);
                    $parent_controller->update_user_profile($primary_data['user_email']->value, $db_data, $service);
                    $prevent_redirection = true;
                } else {
                    $params['paystate'] = 'post_payment';
                    $user_id= $fe_form->post_sub_proc($request->req, $params, $params['is_paid']);
                    $parent_controller->update_user_profile($primary_data['user_email']->value, $db_data, $service);
                }
                if($params['is_paid'] && !empty($user_id)) {
                    do_action('rm_payment_completed', $params['user_email'], $fe_form, $parameters->sub_id);
                }
            } else {
                $params['paystate'] = 'na';
                $user_id= $fe_form->post_sub_proc($request->req, $params);
                $parent_controller->update_user_profile($primary_data['user_email']->value, $db_data, $service);
            }
            if(class_exists( 'WooCommerce' )){
             $service->save_wc_meta($form_id, $db_data, $primary_data['user_email']->value);   
            }
            
            //$email = $service->prepare_email('to_admin', $token, $parameters);
            $submission_data= $submission->get_data();
            if(empty($user_id)){
                if(is_user_logged_in()){
                $current_user= wp_get_current_user();
                $user_id= $current_user->ID;
                }
                else
                {
                    $current_user= get_user_by('email',$primary_data['user_email']->value);
                    if(!empty($current_user)){
                       $user_id= $current_user->ID;  
                    }
                }
            }
            do_action('rm_submission_completed',$form_id,$user_id,$submission_data);
            RM_Email_Service::notify_submission_to_admin($parameters,$token);
            //RM_Utilities::send_mail($email);
            unset($parameters->sub_data);
            $parameters->form_options = $form_options;
            $parameters->form_id= $form_id;
            //$parameters->token= $token;
            //Delete the attachment once mail is sent
            if(file_exists($sub_pdf_loc))
                unlink($sub_pdf_loc);
            if (!$prevent_redirection){ 
                return $service->after_submission_proc($parameters);                 
               // return $service->after_submission_proc($parameters).'<div class="rm_token_no"> Unique Token Number: '.$token.'</div>'; //This must be returned as there is no ob_start here at work.
              
            }

            return $redirection_html;
        } else {
            $data = new stdClass;
            $data->stat_id = "__uninit";//$service->create_stat_entry($params);
            $data->fe_form = $fe_form;
            
            $force_multiple_form = isset($params['force_enable_multiform'])?true:false;
            
            $view = $parent_controller->mv_handler->setView("user_form_nexgen", true);
            return $view->read($data, $force_multiple_form);
        }
    }

    public function test_form_access_v2(RM_Frontend_Form_Base $fe_form, $service, $request, $params, $parent_controller, $edit= false)
    {   
        $form_options = $fe_form->get_form_options();
        $tresp = "RM_FAC_TR_".$fe_form->get_form_id();
        $tstamp = "RM_FAC_TS_".$fe_form->get_form_id();
        $tdi = "rm_fac_di_".$fe_form->get_form_id();
        $factrl = $form_options->access_control;  
        $fail_msg = (isset($factrl->fail_msg) && $factrl->fail_msg) ? $factrl->fail_msg : RM_UI_Strings::get('LABEL_ACTRL_FAIL_MSG_DEF');
        $act_report = new stdClass;
        $act_report->status = 'failed';
        $act_report->html_str = '<div class="rm_fac_resp">'.$fail_msg.'</div>';

        if(isset($factrl->roles) && is_array($factrl->roles))
        {
            $is_allowed = false;
            if(is_user_logged_in()) {
                $curr_user = wp_get_current_user();
                /*
                $curr_user_role = $curr_user->roles[0];
                
                if(in_array($curr_user_role, $factrl->roles))
                {
                    $is_allowed = true;
                }
                */
                
                $curr_user_roles = $curr_user->roles;
                foreach ($curr_user_roles as $curr_user_role) {
                    if(in_array($curr_user_role, $factrl->roles)) {
                        $is_allowed = true;
                        break;
                    }
                }
            } else {
                $act_report->status = 'failed';
                $act_report->html_str = '<div class="rm_fac_resp">'.__('You need to login to access this form', 'registrationmagic-addon').'</div>';
                $act_report->html_str .= do_shortcode('[RM_Login]');
                return $act_report;
            }

            if($is_allowed === false) {
                    $act_report->status = 'failed';
                    $act_report->html_str = '<div class="rm_fac_resp">'.$fail_msg.'</div>';
                    return $act_report;
            } 
        }
        
        if(is_user_logged_in() && !empty($factrl->domain)){
            $is_allowed = false;
            $user = wp_get_current_user();
            $domains = explode(',', $factrl->domain);
            $parts = explode('@',$user->user_email); // Separate string by @ characters (there should be only one)
            $domain = array_pop($parts); // Remove and return the last part, which should be the domain
            // Check if the domain is in our list
            if (!in_array($domain, $domains))
            {
                $act_report->status = 'failed';
                $act_report->html_str = '<div class="rm_fac_resp">'.$fail_msg.'</div>';
                return $act_report;
            }
            else{
                $is_allowed = true;
            }
            
        }
       
        //Check if other access controls are enabled or not.
        if(!isset($factrl->date) && !isset($factrl->passphrase))
        {
            $act_report->status = 'allowed';
            $act_report->html_str = '';
            return $act_report;            
        }
        
        if (isset($_SESSION[$tresp], $_SESSION[$tstamp]) )
        {
            $t2 = time();
            $t1 = intval($_SESSION[$tstamp]);
            
            if(($t2-$t1) < 300)  //Session value is valid only for 300 seconds.
            {
                if ($_SESSION[$tresp] === 'allowed')
                {
                    $act_report->status = 'allowed';
                    $act_report->html_str = '';
                    return $act_report;
                }
                else if(!isset($factrl->passphrase))  //Allow reentry in case it was a pssphrase fail
                {
                    $act_report->status = 'failed';
                    $act_report->html_str = '<div class="rm_fac_resp">'.$fail_msg.'</div>';
                    return $act_report;
                }                
            }
        }
        
        if (isset($request[$tdi]))
        {
            if (RM_Utilities::check_access_control($factrl, $request))
            {         
                $act_report->status = 'allowed';
                $act_report->html_str = '';
                $_SESSION[$tresp] = 'allowed';
                $_SESSION[$tstamp] = time();
                return $act_report;
            } else
            {
                $act_report->status = 'failed';
                $_SESSION[$tresp] = 'failed';
                $_SESSION[$tstamp] = time();
                $act_report->html_str = '<div class="rm_fac_resp">'.$fail_msg.'</div>';
                return $act_report;
            }
        }

        $data = new stdClass;
        $data->actrl = $factrl;
        $data->form_id = $fe_form->get_form_id();
        if(isset($params['without_form_tag']) && $params['without_form_tag'] == true)
            $data->no_form_tag = true;
        if($edit){
            return null;
        }
        $view = $parent_controller->mv_handler->setView("access_control", true);//var_dump($params['force_enable_multiform']);
        
        if(isset($params['force_enable_multiform']))
            $str = $view->read($data, true);
        else
            $str = $view->read($data);
        
        $act_report->status = 'transient';
        $act_report->html_str = $str;
        
        return $act_report;
    }
    
    public function get_price_fields($model, $service, $request, $params, $parent_controller)
    {
        if(isset($request->req['fullfid']))
        {
            $price_field_details = array();
            $form_id = $request->req['fullfid'];
            $from_id_exploded = explode('_',$form_id);
            $form_id = $from_id_exploded[1];
            $fe_form = $parent_controller->form_factory->create_form($form_id);
            $fields = $fe_form->get_fields();
            
            if(is_array($fields))
            {
                foreach($fields as $key => $field)
                {
                    if ($field->get_field_type() === 'Price')
                    {
                        $price_field_details[] = $field->get_field_name();
                    }
                }
            }
            
            echo json_encode($price_field_details);
        }
        die;
    }
    
    //Form to edit prev submission
    public function edit_sub($model, RM_Front_Form_Service $service, $request, $params, $parent_controller) {
        if ($service->is_ip_banned()) {
            return $parent_controller->banned_view();
        }

        global $rm_form_diary;

        /*if (count($rm_form_diary) > 0 && !isset($params['force_enable_multiform']))
            return;*/
       
        $params['form_id'] = $request->req['form_id'];
        if (isset($params['form_id'],$request->req['submission_id']) && $params['form_id'] && $request->req['submission_id']) {
            $form_id = $params['form_id'];
            $request->req['submission_id'] = $service->get_latest_submission_from_group($request->req['submission_id']);
            $fe_form = $parent_controller->form_factory->create_form_prefilled($form_id,$request->req['submission_id'], current_user_can('manage_options'));
            $form_name = 'form_' . $fe_form->get_form_id();
           
        } else {
            return;
        }
    
        $fopts = $fe_form->get_form_options();
        
        //Disabling Form access interception while edit submission
        
        $fac_responce = $parent_controller->test_form_access_v2($fe_form, $service, $request->req, $params,true);
        
        if (!empty($fac_responce) && $fac_responce->status != 'allowed')
            return $fac_responce->html_str;
        
        ///////////////////////////////////
        //Call form specific processing before submission.
        $form_preproc_response = $fe_form->pre_sub_proc($request->req, $params);
        
        /*if (isset($request->req['stat_id']))
            $stat_id = $request->req['stat_id'];
        else
            $stat_id = null;
            */
        if (isset($request->req['rm_form_sub_no']) && $request->req['rm_form_sub_no'])
            $subbed_form_no = $request->req['rm_form_sub_no'];
        else
            $subbed_form_no = null;

        if ($subbed_form_no && ($fe_form->get_form_number() == $subbed_form_no) && $form_preproc_response && $parent_controller->mv_handler->validateForm($form_name . "_" . $subbed_form_no) /*&& !$service->is_browser_reload_duplication($stat_id)*/) {
            $primary_data = $fe_form->get_prepared_data($request->req, 'primary');
 
            if ($service->is_email_banned($primary_data['user_email']->value)) {
                //$service->update_stat_entry($stat_id, 'ban');
                return $parent_controller->banned_view();
            } /*else
                $service->update_stat_entry($stat_id);*/
 
            $db_data = $fe_form->get_prepared_data($request->req, 'dbonly');

            $sub_detail = $service->save_edited_submission($form_id, $request->req['submission_id'], $db_data, $primary_data['user_email']->value);
            do_action('rm_submission_edited', $primary_data['user_email']->value);
            /*
            if (isset($sub_detail))
                $service->update_stat_entry($stat_id, 'update', $sub_detail->submission_id);             
             */
            
            $form_options = $fe_form->get_form_options();
            $factrl = $form_options->access_control;
            if(!empty($factrl->domain)){
                $domains = explode(',', $factrl->domain);
                if(!empty($primary_data['user_email'])){
                    $parts = explode('@',$primary_data['user_email']->value); // Separate string by @ characters (there should be only one)
                    $domain = array_pop($parts); // Remove and return the last part, which should be the domain
                    // Check if the domain is in our list
                    if (!in_array($domain, $domains))
                    {
                        echo $factrl->fail_msg;
                        return;
                    }
                }
            }
            
            if ((int) ($form_options->should_export_submissions) === 1)
            {
                $service->export_to_external_url($form_options->export_submissions_to_url, $db_data);
            }
            if ($form_options->form_is_unique_token)
                $token = $sub_detail->token;
            else
                $token = null;

            $submissions = new RM_Submissions;
            $submissions->load_from_db($sub_detail->submission_id);
            
            $should_attach_sub_pdf = $service->get_setting('admin_notification_includes_pdf');
            
            $sub_pdf_loc = null;
            if($should_attach_sub_pdf == 'yes' || $form_options->enable_dpx=="1")
            {
                $sub_pdf_loc = get_temp_dir().'rm_submission_'.$submissions->get_submission_id().'.pdf';
                //Ouput the pdf to desired location
                $service->output_pdf_for_submission($submissions,array('name' => $sub_pdf_loc, 'type' => 'F'));  
            }
            
            $parameters = new stdClass;
            $parameters->sub_data = $submissions->get_data();
            $parameters->form_name = $fe_form->get_form_name();
            $parameters->sub_id = $sub_detail->submission_id;
            $parameters->form_id = $form_id;
            //Attachments for the mail
            $parameters->attachment = $sub_pdf_loc;
            
            if(class_exists( 'WooCommerce' )) {
                $service->save_wc_meta($form_id, $db_data, $primary_data['user_email']->value);   
            }
            
            //$email = $service->prepare_email('to_admin', $token, $parameters);
            //RM_Email_Service::notify_submission_to_admin($parameters,$token);
            //wp_schedule_single_event( time() + 2, 'rm_after_submission',array($sub_detail,$request->req,$sub_pdf_loc));
            do_action('rm_after_submission',$sub_detail,$request->req,$sub_pdf_loc);

            $params['sub_detail'] = $sub_detail;

            /*
             * Check for payment
             */
            //also call Form specific method after submission
            $prevent_redirection = false;

            $params['paystate'] = 'na';
            $fe_form->post_sub_proc($request->req, $params);
            $parent_controller->update_user_profile($primary_data['user_email']->value, $db_data, $service);
            
            //redirect user to new submissions page
            $form_options->redirection_type = 'url';
            $form_options->redirect_url = current_user_can('manage_options') ? add_query_arg( array('page' => 'rm_submission_view','rm_submission_id' => $sub_detail->submission_id), admin_url('admin.php')) :add_query_arg( 'submission_id',$sub_detail->submission_id, get_permalink());
            
            unset($parameters->sub_data);
            $parameters->form_options = $form_options;
            $parameters->form_id= $request->req['form_id'];
            do_action('rm_submission_edited',$sub_detail->submission_id);
            //Delete the attachment once mail is sent
            if(file_exists($sub_pdf_loc)){
               unlink($sub_pdf_loc);
            }
            if (!$prevent_redirection)
                return $service->after_submission_proc($parameters); //This must be returned as there is no ob_start here at work.
            }//End form valiadtion condition
            else {
                //procedure to render the form if not valid
            $data = new stdClass;
            $data->stat_id = 0;//$service->create_stat_entry($params);
            $data->fe_form = $fe_form;
            $data->submission_id = $request->req['submission_id'];

            $force_multiple_form = isset($params['force_enable_multiform']) ? true : false;

            $view = $parent_controller->mv_handler->setView("user_form_nexgen", true);
            return $view->read($data, $force_multiple_form);
        }
    }
}