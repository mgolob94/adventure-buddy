<?php
if (!defined('WPINC')) {
    die('Closed');
}
wp_enqueue_style( 'rm_material_icons', RM_BASE_URL . 'admin/css/material-icons.css' );
$user = get_user_by('email', $data->submission->user_email);

$user_type = 'guest';
$user_id = 0;
if($user):
    $user_type = get_user_meta($user->ID, 'rm_user_status',true);
    $user_id = $user->ID;
endif;
$form_type_status = $data->form_type_status;
    if(isset($data->payment->id)):
    ?>
    <div class="rmagic rm-payment-report-main">
        <div class="rm-box-title rm-box-mb-25 rm-payment-view-id"><?php _e('Payment ID ','registrationmagic-addon'); echo wp_kses_post($data->payment->id);?></div>
        <!-- First Block-->
        <div class="rm-veiw-payments-card rm-box-border rm-box-white-bg rm-box-mb-25 rm-box-p">
            <div class="rm-payments-header-rows rm-box-row rm-box-py-3">
                <div class="rm-payments-header-col rm-payments-header-column-1 rm-box-col-9">
                    <div class="rm-payments-header-top">
                        <div class="rm-payments-total">
                            <div class="rm-total-price"><?php _e('Total Price: ','registrationmagic-addon');?> <span><?php echo wp_kses_post($data->payment->total_amount);?></span></div>
                            <div class="rm-total-price"></div>
                        </div>
                        <!-- Products Name --->
                        <div class="rm-payments-products-name-qty rm-box-row">
                            <?php 
                            $products = array();
                            $total_qty = array();
                            $top_product = '';
                            $top_qty = '';
                            $total_price = 0;
                            if(isset($data->payment)):
                                $bill = unserialize($data->payment->bill);
                                $billing = $bill->billing;
                                if(!empty($billing)){
                                    foreach($billing as $product){
                                        
                                        $products[] = $product->label;
                                        $total_qty[] = $product->qty;
                                        if($total_price < ($product->price * $product->qty) ){
                                            $top_product = $product->label;
                                            $top_qty = $product->qty;
                                            $total_price = $product->price * $product->qty;
                                        }
                                    }
                                }
                            endif;
                            ?>
                            <div class="rm-payments-product rm-box-col-4 rm-di-flex rm-box-center">
                                <div class="rm-payments-product-title rm-payment-title"><?php _e('PRODUCT NAME: ','registrationmagic-addon');?></div>
                                <div class="rm-payments-product-name"><?php 
                                    if(!empty($top_product)){
                                        echo $top_product;
                                    }else{
                                        _e('Product Not Found.');
                                    }
                                ?></div>
                            </div>
                            <div class="rm-payments-product rm-box-col-2 rm-di-flex rm-box-center">
                                 <div class="rm-payments-quantity rm-payment-title"><?php _e('QUANTITY: ','registrationmagic-addon');?></div>
                              <div class="rm-payments-quantity-value"><?php 
                                    echo esc_html('x '. $top_qty);
                                ?></div>
                            </div>
                            <div class="rm-payments-product-more rm-box-col-6">
                            <?php if(count($products) > 1):?>
                                <a href="#rm_more_products" onclick="CallModalBox(this)"><?php _e('and '. (count($products) -1) .' more','registrationmagic-addon');?></a>
                            <?php endif;?>
                                  </div>
                        </div>
                        
                        <!-- Products Name Ends -->
                        
                        <!-- Payments Status Start-->
                        <div class="rm-payments-status-transc rm-box-row">
                            <div class="rm-payments-gateway rm-box-col-3 rm-di-flex rm-box-center">
                                <div class="rm-payment-title rm-payment-gatway-title"><?php _e('GATEWAY: ','registrationmagic-addon');?></div>
                                <div class="rm-payments-value rm-payment-type-<?php echo wp_kses_post($data->payment->pay_proc);?>"><a href="<?php echo admin_url('admin.php?page=rm_options_payment'); ?>" target="_blank"><?php echo wp_kses_post($data->payment->pay_proc);?></a></div>
                            </div>
                            <div class="rm-payments-trans-id rm-box-col-3 rm-di-flex rm-box-center">
                                <div class="rm-payment-title"><?php _e('ID: ','registrationmagic-addon');?></div>
                                <div class="rm-payments-value"><?php echo esc_html($data->payment->invoice);?></div>
                            </div>
                            <div class="rm-payments-status rm-box-col-3 rm-di-flex rm-box-center">
                                <div class="rm-payment-title"><?php _e('STATUS: ','registrationmagic-addon');?></div>
                                <?php 
                                $status = strtolower($data->payment->status); 
                                if($status == 'completed' || $status == 'succeeded'){
                                    echo '<div class="rm-payments-value payment-status-completed">'.wp_kses_post('Completed').'</div>';
                                }
                                else{
                                    echo '<div class="rm-payments-value payment-status-'.$status.'">'.wp_kses_post(ucfirst($status)).'</div>';
                                }
                                ?>
                            </div>
                            <div class="rm-payments-date rm-box-col-3 rm-di-flex rm-box-center">
                                <div class="rm-payment-title"><?php _e('DATE: ','registrationmagic-addon');?></div>
                                <div class="rm-payments-value"><?php echo esc_html(RM_Utilities::localize_time($data->payment->posted_date,'M j, Y H:i A')); ?></div>
                            </div>
                        </div>
                        <!-- Payments Status Ends -->
                        
                        <!-- Payments Forms Start -->
                        <div class="rm-payments-form-section">
                            <div class="rm-payments-form-section-row rm-box-row">
                                <div class="rm-box-col-6">
                                    <div class="rm-payments-form-name rm-d-flex rm-box-text-right">
                                        <div class="rm-di-flex rm-box-center rm-payment-title"><?php _e('FORM: ','registrationmagic-addon');?></div>
                                        <div class="rm-di-flex rm-box-center rm-payments-value"><a href="<?php echo admin_url('admin.php?page=rm_form_sett_manage&rm_form_id='.$data->form_id); ?>" target="_blank"><?php echo wp_kses_post($data->form_name);?></a></div>
                                    </div>
                                    <div class="rm-payments-submission-id rm-d-flex rm-box-text-right">
                                        <div class="rm-di-flex rm-box-center rm-payment-title"><?php _e('SUBMISSION ID: ','registrationmagic-addon');?></div>
                                        <div class="rm-di-flex rm-box-center rm-payments-value"><a href="<?php echo admin_url('admin.php?page=rm_submission_view&rm_submission_id='.$data->submission->submission_id); ?>" target="_blank"><?php echo wp_kses_post($data->submission->submission_id);?></a></div>
                                    </div>
                                    <div class="rm-payments-submission-date rm-d-flex rm-box-text-right">
                                        <div class="rm-di-flex rm-box-center rm-payment-title"><?php _e('SUBMISSION DATE: ','registrationmagic-addon');?></div>
                                        <div class="rm-di-flex rm-box-center rm-payments-value"><?php echo esc_html(RM_Utilities::localize_time($data->submission->submitted_on,'M j. Y. H:i A')); ?></div>
                                    </div>
                                </div>
                                <div class="rm-box-col-6">
                                    <div class="rm-payments-user-email rm-d-flex rm-box-text-right">
                                        <div class="rm-payment-title rm-di-flex rm-box-center"><?php _e('USER: ','registrationmagic-addon');?></div>
                                        <div class="rm-payments-user-email-id rm-payments-value rm-di-flex rm-box-center">
                                            <?php echo get_avatar($data->submission->user_email)?get_avatar($data->submission->user_email):'<img src="'.RM_IMG_URL.'default_person.png">'; ?>
                                                <?php if($user_id):?>
                                                <a href="<?php echo admin_url('admin.php?page=rm_user_view&user_id='.$user_id); ?>" target="_blank">
                                                    <?php echo wp_kses_post($data->submission->user_email);?>
                                                </a>
                                                <?php else:
                                                    echo wp_kses_post($data->submission->user_email);
                                                endif;?>
                                        </div>
                                    </div> 
                                    <div class="rm-payments-user-status rm-d-flex">
                                        <div class="rm-payment-title rm-di-flex rm-box-center"><?php _e('USER STATUS: ','registrationmagic-addon');?></div>
                                        <?php 
                                        if($form_type_status && !$user_type){
                                            echo '<div  class="rm-payments-value rm-di-flex rm-box-center rm-payment-user-activated">'.wp_kses_post('Activated ','registrationmagic-addon').'</div>';
                                        }elseif (!$form_type_status && !$user_type) {
                                            echo '<div  class="rm-payments-value rm-di-flex rm-box-center rm-payment-user-existing">'.wp_kses_post('Existing User','registrationmagic-addon').'</div>';        
                                        }
                                        elseif($user_type == 'guest'){
                                            echo '<div class="rm-payments-value rm-di-flex rm-box-center rm-payment-user-guest">'.wp_kses_post('Guest','registrationmagic-addon').'</div>';
                                        }
                                        else{
                                            echo '<div class="rm-payments-value rm-di-flex rm-box-center rm-payment-user-pending">'.wp_kses_post('Deactivated','registrationmagic-addon').'</div>';
                                        }
                                        ?>
                                    </div> 
                                    <div class="rm-payments-user-revenue rm-d-flex rm-box-text-right">
                                        <div class="rm-payment-title rm-di-flex"><?php _e('LIFETIME REVENUE: ','registrationmagic-addon');?></div>
                                        <div class="rm-payments-value rm-di-flex rm-box-center"><?php echo wp_kses_post(RM_Utilities::get_formatted_price($data->total_revenue));?></div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <!-- Payments Forms Ends -->
                        <!-- Payments Download Start -->
                        <div class="rm-payments-download-section">
                            <div class="rm-payments-invoice-section rm-payments-download-row rm-box-justify rm-box-row">
                                <div class="rm-payments-download-type rm-di-flex rm-box-center rm-box-col-6 "><span class="rm-payments-icon"> <img  class="rm_submission_icon" alt="" src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'images/svg/payment-invoice-icon.svg'); ?>"> </span> <?php _e('Invoice','registrationmagic-addon');?></div>
                                <div class="rm-payments-download-actions rm-box-col-6 rm-box-text-right">
                                    <?php if($data->enable_invoice){?>
                                    <a href="<?php echo admin_url('admin-ajax.php?rm_submission_id='.$data->submission->get_submission_id().'&action=rm_download_invoice_pdf&rm_sec_nonce='.wp_create_nonce('rm_ajax_secure')); ?>"><span class="rm-svg-invoice-icon"> <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#2271B1"><g><rect fill="none" height="24" width="24"/></g><g><path d="M18,15v3H6v-3H4v3c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2v-3H18z M17,11l-1.41-1.41L13,12.17V4h-2v8.17L8.41,9.59L7,11l5,5 L17,11z"/></g></svg></span> </a>
                                    <a target="_blank" href="<?php echo admin_url('admin-ajax.php?rm_submission_id='.$data->submission->get_submission_id().'&action=rm_download_invoice_pdf&type=I&invoice_id='.$data->payment->invoice.'&rm_sec_nonce='.wp_create_nonce('rm_ajax_secure')); ?>"><span class="rm-svg-invoice-icon" > <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#2271B1"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 6c3.79 0 7.17 2.13 8.82 5.5C19.17 14.87 15.79 17 12 17s-7.17-2.13-8.82-5.5C4.83 8.13 8.21 6 12 6m0-2C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 5c1.38 0 2.5 1.12 2.5 2.5S13.38 14 12 14s-2.5-1.12-2.5-2.5S10.62 9 12 9m0-2c-2.48 0-4.5 2.02-4.5 4.5S9.52 16 12 16s4.5-2.02 4.5-4.5S14.48 7 12 7z"/></svg> </span> </a>
                                    <?php } else {?>
                                    <a href="<?php echo admin_url('admin.php?page=rm_options_manage_invoice');?>"><?php _e('Enable Invoice');?></a>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="rm-payments-submission-section rm-payments-download-row rm-box-justify rm-box-row">
                                <div class="rm-payments-download-type rm-di-flex rm-box-center rm-box-col-6"><span class="rm-payments-icon"> <img  class="rm_submission_icon" alt="" src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'images/svg/payment-invoice-icon.svg'); ?>"> </span>  <?php _e('Form Submission','registrationmagic-addon');?></div>
                                <div class="rm-payments-download-actions rm-box-col-6 rm-box-text-right">
                                    <?php if(defined('REGMAGIC_ADDON')):?>
                                    <a href="<?php echo admin_url('admin-ajax.php?rm_submission_id='.$data->submission->get_submission_id().'&action=rm_print_pdf&rm_sec_nonce='.wp_create_nonce('rm_ajax_secure')); ?>"><span class="rm-svg-invoice-icon"> <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#2271B1"><g><rect fill="none" height="24" width="24"/></g><g><path d="M18,15v3H6v-3H4v3c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2v-3H18z M17,11l-1.41-1.41L13,12.17V4h-2v8.17L8.41,9.59L7,11l5,5 L17,11z"/></g></svg></span> </a> 
                                    <a target="_blank" href="<?php echo admin_url('admin-ajax.php?rm_submission_id='.$data->submission->get_submission_id().'&action=rm_print_pdf&type=I&rm_sec_nonce='.wp_create_nonce('rm_ajax_secure')); ?>"><span class="rm-svg-invoice-icon"> <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#2271B1"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 6c3.79 0 7.17 2.13 8.82 5.5C19.17 14.87 15.79 17 12 17s-7.17-2.13-8.82-5.5C4.83 8.13 8.21 6 12 6m0-2C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 5c1.38 0 2.5 1.12 2.5 2.5S13.38 14 12 14s-2.5-1.12-2.5-2.5S10.62 9 12 9m0-2c-2.48 0-4.5 2.02-4.5 4.5S9.52 16 12 16s4.5-2.02 4.5-4.5S14.48 7 12 7z"/></svg> </span></a> 
                                    <?php endif;?>
                                    <a target="_blank" href="<?php echo admin_url('admin.php?page=rm_submission_view&rm_submission_id='.$data->submission->submission_id);?>"><span class="rm-svg-invoice-icon"> <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#2271B1"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/></svg> </span></a> 
                                </div>
                            </div>
                            <div class="rm-payments-transaction-section rm-payments-download-row rm-box-justify rm-box-row">
                                <div class="rm-payments-download-type rm-di-flex rm-box-center rm-box-col-6"><span class="rm-payments-icon"> <img  class="rm_submission_icon" alt="" src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'images/svg/payment-trans-icon.svg'); ?>"> </span> <?php _e('Transaction Log','registrationmagic-addon');?></div>
                                <div class="rm-payments-download-actions rm-box-col-6 rm-box-text-right">
                                    <a href="#rm_payments_logs" onclick="CallModalBox(this)"><span class="rm-svg-invoice-icon"> <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#2271B1"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 6c3.79 0 7.17 2.13 8.82 5.5C19.17 14.87 15.79 17 12 17s-7.17-2.13-8.82-5.5C4.83 8.13 8.21 6 12 6m0-2C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 5c1.38 0 2.5 1.12 2.5 2.5S13.38 14 12 14s-2.5-1.12-2.5-2.5S10.62 9 12 9m0-2c-2.48 0-4.5 2.02-4.5 4.5S9.52 16 12 16s4.5-2.02 4.5-4.5S14.48 7 12 7z"/></svg> </span> </a>
                                </div>
                            </div>
                        </div>
                        <!-- Payments Forms Ends -->
                    </div>
                </div>
                
                <div class="rm-payments-header-col rm-payments-header-column-2 rm-box-col-3">
                    
                    <div class="rm-box-btn-wrap rm-payments-actions rm-box-text-right">
                        <?php if($data->enable_invoice){?>
                            <div id="rm-payments-send-confirmation-email" class="rm-payment-action-btn" onclick="rm_send_payment_confirmation_email('<?php echo wp_kses_post($data->submission->user_email);?>',<?php echo wp_kses_post($data->form_id);?>,<?php echo wp_kses_post($data->submission->submission_id);?>,<?php echo wp_kses_post($data->payment->id);?>);"><a href="#"><button class="rm-btn-primary rm-btn"><?php _e('Resend Confirmation Email','registrationmagic-addon');?></button></a></div>
                        <?php }else{ ?>
                            <div id="rm-payments-send-confirmation-email" class="rm-payment-action-btn"><a href="#"><button class="rm-btn-primary rm-btn rm-premium-action-bt"><?php _e('Resend Confirmation Email','registrationmagic-addon');?></button></a></div>
                        <?php }?>
                        <div id="rm-payments-add-notes" class="rm-payment-action-btn"><a href="<?php echo admin_url('admin.php?page=rm_note_add&rm_submission_id='.$data->submission->submission_id.'&rm_redirection=rm_payments_view'); ?>" ><button class="rm-btn-primary rm-btn"><?php _e('Add Note','registrationmagic-addon');?></button></a></div>
                        
                        <div id="rm-payments-payment-status" class="rm-payment-action-btn"><a href="#rm_payments_status_update" onclick="CallModalBox(this)"><button class="rm-btn-primary rm-btn"><?php _e('Change Payment Status','registrationmagic-addon');?></button></a></div>
                        <?php if(get_current_user_id() == $user_id){?>
                        <div id="rm-payments-deactivate-user" class="rm-payment-action-btn"><a href="#"><button class="rm-btn-primary rm-btn rm-premium-action-bt"><?php _e('Admin','registrationmagic-addon');?></button></a></div>                        
                        <?php } elseif($user_type == 'guest'){?>
                            <div id="rm-payments-guest-user" class="rm-payment-action-btn rm-payment-disabled"><a href="#" disabled><button class="rm-btn-primary rm-btn"><?php _e('Guest User','registrationmagic-addon');?></button></a></div>
                        <?php } elseif($user_type){?>
                            <div id="rm-payments-activate-user" class="rm-payment-action-btn"><a href="<?php echo admin_url('admin.php?page=rm_payments_view&rm_submission_id='.$data->submission->submission_id.'&rm_user_id='.$user->ID.'&rm_action=activate'); ?>"><button class="rm-btn-primary rm-btn"><?php _e('Activate User','registrationmagic-addon');?></button></a></div>
                        <?php } else{?>
                            <div id="rm-payments-deactivate-user" class="rm-payment-action-btn"><a href="<?php echo admin_url('admin.php?page=rm_payments_view&rm_submission_id='.$data->submission->submission_id.'&rm_user_id='.$user->ID.'&rm_action=deactivate'); ?>"><button class="rm-btn-primary rm-btn"><?php _e('Deactivate User','registrationmagic-addon');?></button></a></div>
                        
                        <?php }?>
                            <div id="rm-payments-delete-payment" class="rm-payment-action-btn"><a onclick="return confirm('<?php _e('Are you sure you want to delete this submission','registrationmagic-addon');?>')" href="<?php echo admin_url('admin.php?page=rm_payments_view&rm_submission_id='.$data->submission->submission_id.'&rm_action=delete'); ?>"><button class="rm-btn-danger rm-btn"><?php _e('Delete Payment','registrationmagic-addon');?></button></a></div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- First Block End-->
        
        <!-- Similar Payments Start-->
        <?php if(isset($data->latest_payments) && !empty($data->latest_payments)):?>
        <div class="rm-report-preview rm-payments-similar">
            <div class="rm-reports-preview-title rm-box-title rm-box-mb-25 rm-payment-view-id"><?php _e('Similar payments');?></div>
              <div class="rm-reports-preview-sub-title rm-box-sub-title rm-box-mb-25"></div>
            <table class="rm-reports-table rm-payments-similar-table">
                <thead>
                    <tr>
                        <th><?php _e('Date','registrationmagic-addon');?></th>
                        <th><?php _e('Product','registrationmagic-addon');?></th>
                        <th><?php _e('User','registrationmagic-addon');?></th>
                        <th><?php _e('Amount','registrationmagic-addon');?></th>
                        <th><?php _e('Status','registrationmagic-addon');?></th>
                        <th><?php _e('Action','registrationmagic-addon');?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach( $data->latest_payments as $latest_payment ){ ?>
                    <tr>
                        <td><?php echo wp_kses_post(RM_Utilities::localize_time($latest_payment->submitted_on,'j M, Y'));?></td>
                        <td><?php 
                            $similar_products = array();
                            $similar_bill = unserialize($latest_payment->bill);
                            $similar_billing = $similar_bill->billing;
                            if(!empty($similar_billing)){
                                foreach($similar_billing as $product){
                                    $similar_products[]= $product->label;
                                }
                                if(!empty($similar_products)){
                                    $similar_products = implode(', ',$similar_products);
                                }
                                if (function_exists('mb_strimwidth')){
                                    echo wp_kses_post(mb_strimwidth($similar_products, 0, 20, "..."));
                                }
                            }
                            ?></td>
                        <td><?php echo wp_kses_post($latest_payment->user_email);?></td>
                        <td><?php echo wp_kses_post(RM_Utilities::get_formatted_price($latest_payment->total_amount));?></td>
                        <td><?php 
                            if(strtolower($latest_payment->status) == 'succeeded'){
                                echo _e('Completed','registrationmagic-addon');
                            }else{
                                echo wp_kses_post($latest_payment->status);
                            }
                        ?></td>
                        <td><a target="_blank" href="<?php echo admin_url('admin.php?page=rm_payments_view&rm_submission_id='.$latest_payment->submission_id);?>"><span class="material-icons"> launch </span></a></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php endif;?>
        <!-- Similar Payments End -->
        
        <!-- Other Payments of this users start-->
        <?php if(isset($data->user_payments) && !empty($data->user_payments)):?>
        <div class="rm-report-preview rm-payments-other">
            <div class="rm-reports-preview-title rm-box-title rm-box-mb-25 rm-payment-view-id"><?php _e('Other payments from this user');?></div>
            <div class="rm-reports-preview-sub-title rm-box-sub-title rm-box-mb-25"></div>
            <table class="rm-reports-table rm-payments-other-table">
                <thead>
                    <tr>
                        <th><?php _e('Date','registrationmagic-addon');?></th>
                        <th><?php _e('Product','registrationmagic-addon');?></th>
                        <th><?php _e('Form','registrationmagic-addon');?></th>
                        <th><?php _e('Amount','registrationmagic-addon');?></th>
                        <th><?php _e('Status','registrationmagic-addon');?></th>
                        <th><?php _e('Action','registrationmagic-addon');?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach( $data->user_payments as $user_payment ){ ?>
                    <tr>
                        <td><?php echo wp_kses_post(RM_Utilities::localize_time($user_payment->submitted_on,'j M, Y'));?></td>
                        <td><?php 
                            $other_products = array();
                            $other_bill = unserialize($user_payment->bill);
                            $other_billing = $other_bill->billing;
                            if(!empty($other_billing)){
                                foreach($other_billing as $product){
                                    $other_products[]= $product->label;
                                }
                                if(!empty($other_products)){
                                    $other_products = implode(', ',$other_products);
                                }
                                if (function_exists('mb_strimwidth')){
                                    echo wp_kses_post(mb_strimwidth($other_products, 0, 20, "..."));
                                }
                            }
                            ?></td>
                        <td><?php echo wp_kses_post($user_payment->form_name);?></td>
                        <td><?php echo wp_kses_post(RM_Utilities::get_formatted_price($user_payment->total_amount));?></td>
                        <td><?php 
                            if(strtolower($user_payment->status) == 'succeeded'){
                                echo _e('Completed','registrationmagic-addon');
                            }else{
                                echo wp_kses_post($user_payment->status);
                            }
                        ?></td>
                        <td><a target="_blank" href="<?php echo admin_url('admin.php?page=rm_payments_view&rm_submission_id='.$user_payment->submission_id);?>"><span class="material-icons"> launch </span></a></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php endif;?>
        <!-- Other Payments End-->
        
        <!-- Other Notes Starts-->
        <?php
        if ($data->notes && (is_object($data->notes) || is_array($data->notes))) :?>

        <div class="rm-pm-admin-notes-section">
            <div class="rm-payment-view-id rm-box-title rm-box-mb-25"><?php _e('Admin Notes');?></div>
            <?php
            foreach ($data->notes as $note) {
                $opt=  maybe_unserialize($note->note_options);
                $note_type=isset($opt->type)?$opt->type:null;
                ?>
                <div class="rm-pm-note" style="border-left: 4px solid #<?php echo maybe_unserialize($note->note_options)->bg_color; ?>">
                    <div class="rm-submission-note-text"><?php echo $note->notes; ?></div>
                    <div class="rm-submission-note-attribute">

                        <?php
                           switch ($note_type) {
                            case 'message' :
                                echo RM_UI_Strings::get('LABEL_SENT_BY') . " <b>" . $note->author . "</b> <em>" . RM_Utilities::localize_time($note->publication_date) . "</em>";
                                break;
                            case 'notification':
                                printf(RM_UI_Strings::get('MSG_SUB_EDITED_BY'), $note->author ?: "the user", RM_Utilities::localize_time($note->publication_date));
                                break;
                            default:
                                echo RM_UI_Strings::get('LABEL_CREATED_BY') . " <b>" . $note->author . "</b> <em>" . RM_Utilities::localize_time($note->publication_date) . "</em>";
                                break;
                        }

                        if ($note->editor)
                            echo " (" . RM_UI_Strings::get('LABEL_EDITED_BY') . " <b>" . $note->editor . "</b> <em>" . RM_Utilities::localize_time($note->last_edit_date) . "</em>";
                        ?>
                    </div>

                    <div class="rm-submission-note-attribute">
                        <?php if ($note_type !== 'message' && $note_type !== 'notification') { ?>
                            <a href="?page=rm_note_add&rm_submission_id=<?php echo $data->submission->get_submission_id(); ?>&rm_note_id=<?php echo $note->note_id; ?>&rm_redirection=rm_payments_view"><?php echo RM_UI_Strings::get('LABEL_EDIT'); ?></a>
                        <?php } ?>
                        <a href="javascript:void(0)" onclick="document.getElementById('rmnotesectionform<?php echo $note->note_id; ?>').submit()"><?php echo RM_UI_Strings::get('LABEL_DELETE'); ?></a>
                    </div>


                    <form method="post" id="rmnotesectionform<?php echo $note->note_id; ?>">
                        <input type="hidden" name="rm_slug" value="rm_note_delete">
                        <input type="hidden" name="rm_redirection" value="rm_payments_view">
                      <input type="hidden" name="rm_note_id" value="<?php echo $note->note_id; ?>"> 

                    </form>
                </div>
                <?php
            }
            ?>
        </div>
        <?php endif;?>
        
        
                <!--- Modal setup Start -->
            
        <div id="rm_payments_logs" class="rm-modal-view" style="display: none;">
            <div class="rm-modal-overlay rm-form-popup-overlay-fade-in"></div>
                <div class="rm_payments_modal_wrap rm-form-popup-out">
                    <div class="rm-modal-titlebar rm-form-template-popup-header">
                        <div class="rm-modal-title">
                            <span class="rm-form-template-subtitle"> <?php _e('Payment Log','registrationmagic-addon'); ?></span>
                        </div>
                        <span  class="rm-modal-close">&times;</span>
                    </div>
                    <div class="rm-modal-container rm-payments-logs-container">
                        <?php if($data->payment->log):?>
                                <table class="rm-payments-logs-table"><?php
                                    $logs = $data->payment->log;
                                    foreach($logs as $key=>$log):?>
                                    <tr>
                                        <td><?php echo wp_kses_post($key);?></td>
                                        <td><?php 
                                            if(is_array($log) || is_object($log)):?>
                                            <table class="table-logs-<?php echo $key;?>">
                                            <?php foreach($log as $ky => $log_a):?>
                                                <tr>
                                                    <td><?php echo wp_kses_post($ky);?></td>
                                                    <td><?php echo esc_html($log_a);?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </table>
                                            <?php
                                            else:
                                                echo wp_kses_post($log);
                                            endif;
                                        ?></td>
                                    </tr>
                                    <?php
                                    endforeach;?>
                                </table>
                        <?php
                            else:?>
                            <div class="rm-reports-no-data-found rmnotice rm-box-border rm-box-mb-25 rm-box-mt-16">No logs found.</div>
                            <?php
                             endif;?>
                    </div>
                </div>
        </div>
        
        <!-- Products List More-->
        <div id="rm_more_products" class="rm-modal-view" style="display: none;">
            <div class="rm-modal-overlay rm-form-popup-overlay-fade-in"></div>
                <div class="rm_payments_modal_wrap rm-form-popup-out">
                    <div class="rm-modal-titlebar rm-form-template-popup-header">
                        <div class="rm-modal-title">
                            <span class="rm-form-template-subtitle"> <?php _e('All Products','registrationmagic-addon'); ?></span>
                        </div>
                        <span  class="rm-modal-close">&times;</span>
                    </div>
                    <div class="rm-modal-container rm-payments-more-container">                
                        <table class="rm-payments-more-products">
                            <thead>
                                <tr>
                                    <th><?php _e('Product Name','registrationmagic-addon');?></th>
                                    <th><?php _e('Quantity','registrationmagic-addon');?></th>
                                    <th><?php _e('Price','registrationmagic-addon');?></th>
                                    <th><?php _e('Total','registrationmagic-addon');?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($billing as $product):?>
                                <tr>
                                    <td><?php echo wp_kses_post($product->label);?></td>
                                    <td><?php echo wp_kses_post($product->qty);?></td>
                                    <td><?php echo wp_kses_post(RM_Utilities::get_formatted_price($product->price));?></td>
                                    <td><?php echo wp_kses_post(RM_Utilities::get_formatted_price($product->price * $product->qty));?></td>
                                </tr>
                                <?php
                                 endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Payment Status Update Model -->
            
        <div id="rm_payments_status_update" class="rm-modal-view" style="display: none;">
                <div class="rm-modal-overlay rm-form-popup-overlay-fade-in"></div>
                <div class="rm_payments_modal_wrap rm-form-popup-out">
                    <div class="rm-modal-titlebar rm-form-template-popup-header">
                        <div class="rm-modal-title">
                            <span class="rm-form-template-subtitle"> <?php _e('Update Payment Status','registrationmagic-addon'); ?></span>
                        </div>
                        <span  class="rm-modal-close">&times;</span>
                    </div>
                    <div class="rm-modal-container rm-payments-more-container">                
                        <div id="rm_pms_admin_status_handler">
                            <div id="rm_pms_edit_payment_status_popup">
                                <div class="rm_pms_edit_payment_row">
                                    <label class="rm_sub_edit_label"><?php _e( 'Status', 'registrationmagic-addon' ); ?></label>
                                        <div class="rm_sub_edit_input">
                                            <?php $status_list = array("pending"=>"Pending","success"=>"Completed","cancel"=>"Canceled","refund"=>"Refunded");?>
                                            <select id="rm_pms_select_payment_status">
                                            <?php foreach($status_list as $key => $value): 
                                                $current_status = $data->payment->status;
                                                $selected_status = '';
                                                if(strtolower($current_status) == strtolower($value) || strtolower($current_status)=='succeeded' ){
                                                    $selected_status = 'selected';
                                                }
                                            ?>
                                                <option value="<?php echo $key;?>" <?php echo $selected_status; ?>><?php _e( $value, 'registrationmagic-addon' ) ?></option>
                                            <?php endforeach; ?>
                                            </select>
                                        </div>
                                </div>
                                <div class="rm_pms_edit_payment_row">
                                    <label class="rm_sub_edit_label"><?php _e( 'Note', 'registrationmagic-addon' ) ?></label>
                                        <div class="rm_sub_edit_input"> 
                                            <textarea id="rm_pms_payment_note" placeholder="<?php _e( 'Enter details such as Check number etc. These notes will show up in transaction details.', 'registrationmagic-addon' ) ?>"></textarea>
                                        </div>
                                </div>
                                <div class="rm_pms_edit_payment_row">
                                   
                                    <div class="rm_sub_edit_input em-payment-footer-model rm-box-btn-wrap">
                                        <a href="" id="rm_pms_status_update_popup_close"><?php _e("Cancel","registrationmagic-addon"); ?></a>
                                        <button id="rm_payment_status_update" class="rm-btn" type="button" onclick="rm_update_payment_details_psm(<?php echo $data->payment->id; ?>,'<?php _e("Status Successfully Updated.","registrationmagic-addon"); ?>','<?php _e("Error Occured","registrationmagic-addon"); ?>')"><?php _e("Update","registrationmagic-addon"); ?></button>
                                        </div>
                                    </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        <!-- Modal Setup End-->
        
        
        
    </div>
    



    
    <?php else:?>
    <div class="rmagic rm-hide-version-number">
        <div class="rm-reports-no-data-found rmnotice rm-box-border rm-box-mb-25"><?php _e('No payment found.','registrationmagic-addon');?></div>
    </div> 
    <?php endif;?>



<script>
    function CallModalBox(ele) {
        jQuery(jQuery(ele).attr('href')).toggle().find("input[type='text']").focus();
          if(jQuery(ele).attr('href')=='#rm_more_products' || jQuery(ele).attr('href')=='#rm_payments_logs' || jQuery(ele).attr('href')=='#rm_payments_status_update'){
            jQuery('.rmagic .rm_payments_modal_wrap').removeClass('rm-form-popup-out');
            jQuery('.rmagic .rm_payments_modal_wrap').addClass('rm-form-popup-in');
            
            jQuery('.rm-modal-overlay').removeClass('rm-form-popup-overlay-fade-out');
            jQuery('.rm-modal-overlay').addClass('rm-form-popup-overlay-fade-in');
          }
    }
    jQuery('.rm-modal-close, .rm-modal-overlay, #rm_pms_status_update_popup_close').click(function () {
        setTimeout(function(){
            jQuery('.rm-modal-view').hide();
        }, 400);
              
    });
    
    function rm_update_payment_details_psm(pproc_id,success_msg, error_msg) {
    
        var status = jQuery("#rm_pms_select_payment_status").val();
        var note = jQuery("#rm_pms_payment_note").val().toString().trim();

        var data = {
                        action: 'rm_olp_update_payment',
                        pproc_id: pproc_id,
                        pay_status: status,
                        pay_note: note
                   };
        jQuery("#rm_payment_status_update").addClass('rm_payment_status_updating').text('Loading...');           
        jQuery.post(ajaxurl, data, function(resp){
            jQuery("#rm_payment_status_update").removeClass('rm_payment_status_updating').text('Update'); 
            if(resp == 'success'){
                alert(success_msg);
                jQuery('.rm-modal-view').hide();
                window.location.reload();
            }
            else{
                
                alert(error_msg);
            }    
            
        });
    }
    function rm_send_payment_confirmation_email(email_id, form_id, submission_id, payment_id) {
    

        var data = {
                        action: 'rm_send_payment_confirmation',
                        rm_email_id: email_id,
                        rm_form_id: form_id,
                        rm_submission_id: submission_id,
                        rm_payment_id: payment_id
                   };
        jQuery("#rm-payments-send-confirmation-email").addClass('rm_payment_email_sending');
        jQuery("#rm-payments-send-confirmation-email button").text('Sending...');
        jQuery.post(ajaxurl, data, function(resp){
            if(resp == 'success'){
                alert('Successfully Sent.');
                jQuery("#rm-payments-send-confirmation-email").removeClass('rm_payment_email_sending');
                jQuery("#rm-payments-send-confirmation-email button").text('Resent Confirmation Email');
            }
            else{
                alert('Failed to send the email. Please retry or check your mail settings in Global Settings area.');
                jQuery("#rm-payments-send-confirmation-email").removeClass('rm_payment_email_sending');
                jQuery("#rm-payments-send-confirmation-email button").text('Resent Confirmation Email');
            }
        });
    }
</script>