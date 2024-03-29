<?php
if (!defined('WPINC')) {
    die('Closed');
}

echo '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">';
if (!empty($data->show_otp))  // 2 Factor OTP form
{
    echo __('<div class="rmagic"><div class="rmcontent rm-login-wrapper">', 'registrationmagic-addon');
    $form = new RM_PFBC_Form($data->otp_form_slug);
    $form->configure(array(
        "prevent" => array("bootstrap", "jQuery"),
        "action" => "",
        "style" => isset($data->design['style_form'])?$data->design['style_form']:null
    ));
    
    if(isset($data->design['placeholder_css'])){		
        $p_css = '<style>'.str_replace("::-", ' #'.$data->otp_form_slug.' ::-', wp_kses_post($data->design['placeholder_css']));		
        $p_css = str_replace("}:-", '} #'.$data->otp_form_slug.' ::-', $p_css).'</style>';	
        $form->addElement(new Element_HTML($p_css));		
    }
    if(isset($data->design['style_label'])){	
         $p_css = "<style>#$data->otp_form_slug .rmrow .rmfield label { ".$data->design['style_label']." }</style>";
         $form->addElement(new Element_HTML($p_css));		
    }
    if(isset($data->design['text_focus_color'])){
        $p_css = "<style>#$data->otp_form_slug .rmrow .rminput input[type=text]:focus, #$data->otp_form_slug .rmrow .rminput input[type=password]:focus { color: ".$data->design['text_focus_color']." !important; }</style>";
        $form->addElement(new Element_HTML($p_css));
    }
    if(isset($data->design['field_bg_focus_color'])){
        $p_css = "<style>#$data->otp_form_slug .rmrow .rminput input[type=text]:focus, #$data->otp_form_slug .rmrow .rminput input[type=password]:focus { background-color: ".$data->design['field_bg_focus_color']." !important; }</style>";
        $form->addElement(new Element_HTML($p_css));
    }
    if(isset($data->design['btn_hover_color'])){
        $p_css = "<style>#$data->otp_form_slug .buttonarea input[type=submit]:hover { background-color: ".$data->design['btn_hover_color']." !important; }</style>";
        $form->addElement(new Element_HTML($p_css));
    }
    $form->addElement(new Element_Hidden("rm_slug", "rm_login_form"));
    $form->addElement(new Element_Hidden("username", $data->username));
    if(!$data->otp_error){
        $form->addElement(new Element_HTML('<div class="rm-otp-msg">'.$data->auth_options['msg_above_otp']));
        if(!empty($data->auth_options['en_resend_otp']) && isset($data->username) && !empty($data->auth_options['otp_resend_limit'])){
            //$form->addElement(new Element_HTML('<a href="javascript:void(0)"><div class="rm-resend-otp-liml" onclick="rm_regernate_otp(this,\''.$data->username.'\')">'.$data->auth_options['otp_resend_text'].'</div></a>'));
            $form->addElement(new Element_HTML('<div class="rm-resend-otp-liml" onclick="rm_regernate_otp(this,\''.$data->username.'\')"><a>'.$data->auth_options['otp_resend_text'].'</a></div>'));
        }
        $form->addElement(new Element_HTML('</div>'));
    }
    $form->addElement(new Element_Textbox($data->auth_options['otp_field_label'], "auth_otp", array("required" => "1", "placeholder" => $data->auth_options['otp_field_label'],'style'=>isset($data->design['style_textfield'])?$data->design['style_textfield']:null)));
    $form->addElement(new Element_HTML('<div class="rmrow fa_otp_error"></div>'));
    $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_LOGIN'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn rm_login_btn", "name" => "submit",'style'=>isset($data->design['style_btnfield'])?$data->design['style_btnfield']:null)));
    $form->render();
    echo __('</div></div>', 'registrationmagic-addon');
} 
else // Normal form with username and password
{
    if(!empty($data->ban)){
        echo $data->ban_error_msg;
        return;
    }
    
    $form = new RM_PFBC_Form($data->login_form_slug);
    $form->configure(array(
        "prevent" => array("bootstrap", "jQuery"),
        "action" => "",
         "style" => isset($data->design['style_form'])?$data->design['style_form']:null
    ));
    
    if(isset($data->design['placeholder_css'])){		
         $p_css = '<style>'.str_replace("::-", ' #'.$data->login_form_slug.' ::-', wp_kses_post($data->design['placeholder_css']));		
         $p_css = str_replace("}:-", '} #'.$data->login_form_slug.' ::-', $p_css).'</style>';		
          $form->addElement(new Element_HTML($p_css));		
    }
    if(isset($data->design['style_label'])){	
         $p_css = "<style>#$data->login_form_slug .rmrow .rmfield label { ".$data->design['style_label']." }</style>";
         $form->addElement(new Element_HTML($p_css));		
    }
    if(isset($data->design['text_focus_color'])){
        $p_css = "<style>#$data->login_form_slug .rmrow .rminput input[type=text]:focus, #$data->login_form_slug .rmrow .rminput input[type=password]:focus { color: ".$data->design['text_focus_color']." !important; }</style>";
        $form->addElement(new Element_HTML($p_css));
    }
    if(isset($data->design['field_bg_focus_color'])){
        $p_css = "<style>#$data->login_form_slug .rmrow .rminput input[type=text]:focus, #$data->login_form_slug .rmrow .rminput input[type=password]:focus { background-color: ".$data->design['field_bg_focus_color']." !important; }</style>";
        $form->addElement(new Element_HTML($p_css));
    }
    if(isset($data->design['btn_hover_color'])){
        $p_css = "<style>#$data->login_form_slug .buttonarea input[type=submit]:hover { background-color: ".$data->design['btn_hover_color']." !important; }</style>";
        $form->addElement(new Element_HTML($p_css));
    }
    
    $form->addElement(new Element_Hidden("rm_slug", "rm_login_form"));
    if (isset($data->twitter)) {
        include_once(RM_ADDON_EXTERNAL_DIR . "twitter/inc/twitteroauth.php");
        $connection = new TwitterOAuth($data->twitter['tw_consumer_key'], $data->twitter['tw_consumer_secret'], $_SESSION['token'], $_SESSION['token_secret']);
        $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
        if ($connection->http_code == '200') {
            //Redirect user to twitter
            $_SESSION['status'] = 'verified';
            $_SESSION['request_vars'] = $access_token;

            //Insert user into the database
            $user_info = $connection->get('account/verify_credentials', array('include_email' => 'true'));
            unset($_SESSION['token']);
            unset($_SESSION['token_secret']);
            
            $user_service = new RM_User_Services;
            $user_service->social_login_using_email_direct($user_info->email, $user_info->name, "twitter");
        }
    } 
    else 
    {
        if (isset($data->fields)) 
        {
            foreach ($data->fields as $field) {
               if(!empty($field['input_selected_icon_codepoint'])){
                $f_icon = new stdClass;
                $f_icon->codepoint = isset($field['input_selected_icon_codepoint']) ? $field['input_selected_icon_codepoint'] : '';
                $f_icon->fg_color = isset($field['icon_fg_color']) ? $field['icon_fg_color'] : '000000';
                $f_icon->bg_color = isset($field['icon_bg_color']) ? $field['icon_bg_color'] : 'ffffff';
                $f_icon->shape = isset($field['icon_shape']) ? $field['icon_shape'] : 'square';
                $f_icon->bg_alpha = isset($field['icon_bg_alpha']) ? $field['icon_bg_alpha'] : 1.0;
                $radius='';
                 if($f_icon->shape == 'square')
                     $radius = '0px';
                 else if($f_icon->shape == 'round')
                     $radius = '100px';
                 else if($f_icon->shape == 'sticker')
                     $radius = '4px';

                 $bg_r = intval(substr($f_icon->bg_color,0,2),16);
                 $bg_g = intval(substr($f_icon->bg_color,2,2),16);
                 $bg_b = intval(substr($f_icon->bg_color,4,2),16);

                 $icon_style = "style=\"padding:5px;color:#{$f_icon->fg_color};background-color:#{$f_icon->bg_color};border-radius:{$radius};\"";
                 $field['field_label']= '<span><i class="material-icons rm_front_field_icon" ' . $icon_style . ' id="id_show_selected_icon" data-opacity="'.$f_icon->bg_alpha.'">' . $f_icon->codepoint . ';</i></span>' . $field['field_label'];
               } 
               
                if ($field['field_type'] == 'username') {
                    if(isset($this->field_options)){
                        $this->x_opts = (object)array('icon' => $this->field_options->icon);
                    }
                   $form->addElement(new Element_Textbox($field['field_label'], "username", array("required" => "1","class"=>$field['field_css_class'], "placeholder" => $field['placeholder'],'style'=>isset($data->design['style_textfield'])?$data->design['style_textfield']:null)));
                } else if ($field['field_type'] == 'password') {
                    $form->addElement(new Element_Password($field['field_label'], "pwd", array("required" => "1", "class"=>$field['field_css_class'], "placeholder" => $field['placeholder'],'style'=>isset($data->design['style_textfield'])?$data->design['style_textfield']:null)));
                } else {
                    /* Get widget data in field options format to comply with existing field structure */
                    $login_model = new RM_Login_Fields();
                    $login_model->initialize($field['field_type']);
                    $field_object = (object) $login_model->get_as_field_options($field);
                    $field_object->field_value = isset($field_object->field_value) ? $field_object->field_value : '';
                    $field_object->field_type = $field['field_type'];

                    // Create field factory object 
                    $field_factory = new RM_Field_Factory_Addon($field_object, array(), false, true);
                    $field_factory->set_field_options($field_object);

                    $method = "create_" . strtolower($field_object->field_type) . "_field";
                    $form->addElement($field_factory->$method()->get_pfbc_field());
                }
            }
        }

        $form->addElement(new Element_HTML('<div class="rmrow"><div class="rmfield"></div><div class="rminput"><ul class="rmradio" style="list-style:none;"><li class="rm-login-remember"> <input id="rm_login_form-element-3-0" type="checkbox" name="remember[]" value="1" checked="checked"><label for="rm_login_form-element-3-0"><span>'.__('Remember Me','registrationmagic-addon').'</span></label> </li> </ul></div></div>'));
        
        
        /*
         * Checking if recpatcha is enabled
         */
        if (get_option('rm_option_enable_captcha') == "yes" && !empty($data->show_captcha))
            $form->addElement(new Element_Captcha());
        
        
        if($data->buttons['align']=='left' || $data->buttons['align']=='right'){
            if(empty($data->design['style_btnfield'])){
                $data->design['style_btnfield']='float:'.$data->buttons['align'];
            }
            else
            {
                $data->design['style_btnfield']=$data->design['style_btnfield'].';float:'.$data->buttons['align'];
            }
        }
        $option_model= new RM_Options();
        $default_registration_url= $option_model->get_value_of('default_registration_url');
        
        if(!empty($data->buttons['display_register']) && !empty($default_registration_url) && $data->buttons['align']=='right'){
            $reg_btn_label= !empty($data->buttons['register_btn']) ? $data->buttons['register_btn'] : RM_UI_Strings::get('LABEL_REGISTER');
            $reg_url= get_permalink($default_registration_url);
            $form->addElement(new Element_Button($reg_btn_label, "button", array("id" => "rm_register_btn", "class" => "rm_btn",'onclick'=>"location.href='$reg_url'",'style'=>isset($data->design['style_btnfield'])?$data->design['style_btnfield']:null)));
        }
        
        $btn_label= !empty($data->buttons['login_btn'])?$data->buttons['login_btn']:RM_UI_Strings::get('LABEL_LOGIN');
        $form->addElement(new Element_Button($btn_label, "submit", array("id" => "rm_submit_btn", "class" => "rm_btn rm_login_btn", "name" => "submit",'style'=>isset($data->design['style_btnfield'])?$data->design['style_btnfield']:null)));
        
        
        if(!empty($data->buttons['display_register']) && !empty($default_registration_url) && $data->buttons['align']!='right'){
            $reg_btn_label= !empty($data->buttons['register_btn']) ? $data->buttons['register_btn'] : RM_UI_Strings::get('LABEL_REGISTER');
            $reg_url= get_permalink($default_registration_url);
            $form->addElement(new Element_Button($reg_btn_label, "button", array("id" => "rm_register_btn", "class" => "rm_btn",'onclick'=>"location.href='$reg_url'",'style'=>isset($data->design['style_btnfield'])?$data->design['style_btnfield']:null)));
        }
        
        
        if(!empty($data->en_pwd_recovery)){
            $form->addElement(new Element_HTML('<div class="rm_forgot_pass"><a href="'.get_permalink($data->recovery_page).'" target="blank">' . $data->recovery_link_text . '</a></div>'));
        }
        

        /*
         * Render the form if user is not logged in
         */
        
        ?>
        <div class='rmagic'>    
            <div class='rmcontent rm-login-wrapper'>
                <?php if (!is_user_logged_in() || (isset($_GET['form_prev']) && $_GET['form_prev']==1 && isset($_GET['form_type']) && $_GET['form_type']=='login')) : ?>
                    <div class="rm-thirdp-login-button-wrap">
                        <?php
                        echo $data->google_html;
                        echo $data->facebook_html;
                        echo $data->linkedin_html;
                        echo $data->windows_html;
                        echo $data->twitter_html;
                        echo $data->instagram_html;
                        ?>
                    </div>
                    <?php 
                    $form->render();
                    ?>

                <?php else : ?> 
                    <?php include('template_rm_logged_in_view.php'); ?>
                <?php endif; ?>
            </div>
        </div> 

    <?php } ?>

<?php } ?>

<?php
/*
if(!empty($_REQUEST['hidden_forms_id'])){
    foreach($_REQUEST['hidden_forms_id'] as $forms_id){
        if(!isset($data->otp_form_submit)){
            echo '<script>jQuery(document).ready(function(){jQuery("#'.$forms_id.'").html("<div class=\'rm-login-attempted-notice\'>'.__('Note: You are already attempting login using a different login form on this page. To keep your logging experience simple and secure, this login form in no longer accessible. Please continue the login process using the form with which you attempted login before the page refresh.','registrationmagic-addon').'</div>")});</script>';
        }
    }
}

if(!empty($data->hidden_forms)){
    foreach($data->hidden_forms as $forms_id){
        if(!isset($data->otp_form_submit)){
           echo '<script>jQuery(document).ready(function(){jQuery("#'.$forms_id.'").html("<div class=\'rm-login-attempted-notice\'>'.__('Note: You are already attempting login using a different login form on this page. To keep your logging experience simple and secure, this login form in no longer accessible. Please continue the login process using the form with which you attempted login before the page refresh.','registrationmagic-addon').'</div>")});</script>';
        }
    }
}
*/


