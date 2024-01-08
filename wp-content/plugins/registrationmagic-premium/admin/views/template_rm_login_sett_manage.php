<?php
if (!defined('WPINC')) {
    die('Closed');
}
?>
<link rel="stylesheet" type="text/css" href="<?php echo RM_BASE_URL . 'admin/css/'; ?>style_rm_form_dashboard.css">
<link rel="stylesheet" type="text/css" href="<?php echo RM_ADDON_BASE_URL . 'admin/css/'; ?>style_rm_form_dashboard.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<pre class="rm-pre-wrapper-for-script-tags"><?php wp_enqueue_script('rm_form_dashboard_js', RM_BASE_URL . 'admin/js/script_rm_form_dashboard.js', array(), RM_PLUGIN_VERSION, false); wp_localize_script('rm_form_dashboard_js', 'rm_admin_vars', array('nonce'=>wp_create_nonce('rm_ajax_secure'))); ?></pre>
<pre class='rm-pre-wrapper-for-script-tags'><script>
    //Takes value of various status variables (form_id, timeline_range) and reloads page with those parameteres updated.
    function rm_refresh_stats(){
    var trange = jQuery('#rm_stat_timerange').val();
    if(typeof trange == 'undefined')
        trange = <?php echo $data->timerange; ?>;
    window.location = '?page=rm_login_sett_manage&rm_tr='+trange;
}
</script></pre>
<div class="rm-form-configuration-wrapper rm-login-configuration-wrap">
    <div class="rm-grid-top dbfl">
        <div class="rm-grid-title difl"><?php _e('Login Form', 'registrationmagic-addon'); ?><span class="rm-login-form-guide"><a href="https://registrationmagic.com/wordpress-user-login-plugin-guide/" target="_blank"><?php _e('Login Form Guide', 'registrationmagic-addon'); ?><span class="dashicons dashicons-book-alt"></span></a></span></div>
        <!--    Forms toggle-->
    <div class="rm-fd-form-toggle difr" id="rm_form_toggle">
        <?php echo RM_UI_Strings::get('LABEL_TOGGLE_FORM'); ?>
        <select onchange="rm_fd_switch_form(jQuery(this).val())">
            <?php
            echo "<option selected value='rm_login_form'>".__('Login Form','registrationmagic-addon')."</option>";
            foreach ($data->all_forms as $form_id => $form_name):
                echo "<option value='$form_id'>$form_name</option>";
            endforeach;
            ?>
        </select>
    </div>
        
    </div>
   
    <div class="rm-grid difl">
        <div class="rm-grid-section dbfl" id="rm_tour_timewise_stats">
            <div class="rm-grid-section-title dbfl rm-box-title"><?php _e('Login Success vs. Failures Graph over time', 'registrationmagic-addon'); ?></div>
            <div class="rm-timerange-toggle rm-fd-form-toggle rm-timerange-dashboard">
                <?php echo RM_UI_Strings::get('LABEL_SELECT_TIMERANGE'); ?>
                <select id="rm_stat_timerange" onchange="rm_refresh_stats()">
                    <?php
                    $trs = array(7,30,60,90);
                    foreach($trs as $tr){
                        echo "<option value=$tr";
                        if($data->timerange == $tr)
                            echo " selected";
                        printf(">".RM_UI_Strings::get("STAT_TIME_RANGES")."</option>",$tr);
                    }
                    ?>
                </select>
            </div>
            <canvas class="rm-box-graph" id="rm_subs_over_time_chart_div"></canvas>
        </div>
     
        <div class="rm-grid-section dbfl">
            <div class="rm-grid-section-title dbfl">
                <?php echo RM_UI_Strings::get('FD_SEC_1_TITLE'); ?>
            </div>

            <div class="rm-grid-icon difl" id="rm-customfields-icon">
                <a href="<?php echo admin_url('admin.php?page=rm_login_field_manage'); ?>" class="rm_fd_link">    
                    <div class="rm-grid-icon-area dbfl">
                        <div class="rm-grid-icon-badge"><?php echo $data->field_count; ?></div>
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>form-custom-fields.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php echo RM_UI_Strings::get('FD_LABEL_LOGIN_FORM_FIELDS'); ?></div>
                </a>
            </div>

            <div class="rm-grid-icon difl" id="rm-design-icon"> 
                <a href="<?php echo admin_url('admin.php?page=rm_login_field_view_sett'); ?>" class="rm_fd_link">   
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>form-view.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php echo RM_UI_Strings::get('FD_LABEL_DESIGN'); ?></div>
                </a>
            </div>

            <div class="rm-grid-icon difl" id="rm-logged-in-view"> 
                <a href="<?php echo admin_url('admin.php?page=rm_login_view'); ?>" class="rm_fd_link">   
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>rm-loggedin-view.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Logged In View', 'registrationmagic-addon'); ?></div>
                </a>
            </div>

        </div>

        <!-- Configure  -->
        <div class="rm-grid-section dbfl" id="rm-general-icon">
            <div class="rm-grid-section-title dbfl">
                <?php echo RM_UI_Strings::get('FD_SEC_2_TITLE'); ?>               
            </div>

            <div class="rm-grid-icon difl" id="rm-general-settings">
                <a href="<?php echo admin_url('admin.php?page=rm_login_sett_redirections') ?>" class="rm_fd_link">    
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>rm-login-redirections.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Redirections', 'registrationmagic-addon'); ?></div>
                </a>
            </div>


            <div class="rm-grid-icon difl" id="rm-accounts-icon">
                <a href="<?php echo admin_url('admin.php?page=rm_login_val_sec') ?>" class="rm_fd_link">    
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>rm-validation-and-security.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Validation & Security', 'registrationmagic-addon'); ?></div>

                </a>
            </div>  

            <div class="rm-grid-icon difl" id="rm-postsubmit-icon">
                <a href="<?php echo admin_url('admin.php?page=rm_login_recovery') ?>" class="rm_fd_link">    
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>rm-password-recovery.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Password Recovery', 'registrationmagic-addon'); ?></div>
                </a>
            </div>  

            <div class="rm-grid-icon difl" id="rm-autoresponder-icon">
                <a href="<?php echo admin_url('admin.php?page=rm_login_two_factor_auth') ?>" class="rm_fd_link">    
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>rm-two-factor-authentication.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Two-Factor Authentication', 'registrationmagic-addon'); ?></div>
                </a>
            </div> 

            <div class="rm-grid-icon difl" id="rm-limits-icon">
                <a href="<?php echo admin_url('admin.php?page=rm_login_email_temp') ?>" class="rm_fd_link">    
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>email_templates.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Email Templates', 'registrationmagic-addon'); ?></div>
                </a>
            </div>  
        </div>
        <!-- Configure  Ends here-->

        <!-- Publish Section -->
        <div class="rm-grid-section dbfl" id="rm-publish-section">
            <div class="rm-grid-section-title dbfl">
                <?php echo RM_UI_Strings::get('FD_SEC_4_TITLE'); ?>
            </div>            

            <div class="rm-grid-icon difl">
                <a href="javascript:void(0)" class="rm_fd_link rm_publish_popup_link" data-publish_type="login">   
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>publish_login.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Login Box', 'registrationmagic-addon'); ?></div>
                </a>
            </div> 
            <div class="rm-grid-icon difl">
                <a href="javascript:void(0)" class="rm_fd_link rm_publish_popup_link" data-publish_type="loginbtn">
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>login_btn.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Login Button', 'registrationmagic-addon'); ?></div>
                </a>
            </div>
            <div class="rm-grid-icon difl">
                <a href="javascript:void(0)" class="rm_fd_link rm_publish_popup_link" data-publish_type="otp">   
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>publish_otp.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('OTP Login', 'registrationmagic-addon'); ?></div>
                </a>
            </div> 
            <div class="rm-grid-icon difl">
                <a href="javascript:void(0)" class="rm_fd_link rm_publish_popup_link" data-publish_type="magicpopup">   
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>publish_magicpopup.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Magic Popup', 'registrationmagic-addon'); ?></div>
                </a>
            </div> 
        </div>
        <!-- Publish ends here -->

        <!-- Integrate section -->
        <div class="rm-grid-section dbfl" id="rm-thirdparty-section">
            <div class="rm-grid-section-title dbfl">
                <?php echo RM_UI_Strings::get('FD_SEC_3_TITLE'); ?>
            </div>

            <div class="rm-grid-icon difl">  
                <a href="<?php echo admin_url('admin.php?page=rm_login_integrations&type=fb'); ?>" class="rm_fd_link">  
                    <div class="rm-grid-icon-area rm-grid-icon-fb dbfl">
                        <i class="fa fa-facebook-square"></i>
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Facebook', 'registrationmagic-addon'); ?></div>

                </a>
            </div> 

            <div class="rm-grid-icon difl"> 
                <a href="<?php echo admin_url('admin.php?page=rm_login_integrations&type=tw'); ?>" class="rm_fd_link">   
                    <div class="rm-grid-icon-area  rm-grid-icon-twitter dbfl">
                        <i class="fa fa-twitter"></i>
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Twitter', 'registrationmagic-addon'); ?></div>

                </a>
            </div> 

            <div class="rm-grid-icon difl">  
                <a href="<?php echo admin_url('admin.php?page=rm_login_integrations&type=win'); ?>" class="rm_fd_link">  
                    <div class="rm-grid-icon-area rm-grid-icon-windows dbfl">
                         <i class="fa fa-windows"></i>
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Windows Live', 'registrationmagic-addon'); ?> </div>
                </a>
            </div>
            
            <div class="rm-grid-icon difl">  
                <a href="<?php echo admin_url('admin.php?page=rm_login_integrations&type=inst'); ?>" class="rm_fd_link">  
                    <div class="rm-grid-icon-area rm-grid-icon-insta dbfl">
                        <i class="fa fa-instagram"></i>
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Instagram', 'registrationmagic-addon'); ?></div>
                </a>
            </div>
            
            <div class="rm-grid-icon difl">  
                <a href="<?php echo admin_url('admin.php?page=rm_login_integrations&type=google'); ?>" class="rm_fd_link">  
                    <div class="rm-grid-icon-area rm-grid-icon-google-p dbfl">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="42" height="42">
  <g transform="matrix(1, 0, 0, 1, 27.009001, -39.238998)">
    <path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.574 55.229 -9.424 56.479 -10.684 57.329 L -10.684 60.329 L -6.824 60.329 C -4.564 58.239 -3.264 55.159 -3.264 51.509 Z"/>
    <path fill="#34A853" d="M -14.754 63.239 C -11.514 63.239 -8.804 62.159 -6.824 60.329 L -10.684 57.329 C -11.764 58.049 -13.134 58.489 -14.754 58.489 C -17.884 58.489 -20.534 56.379 -21.484 53.529 L -25.464 53.529 L -25.464 56.619 C -23.494 60.539 -19.444 63.239 -14.754 63.239 Z"/>
    <path fill="#FBBC05" d="M -21.484 53.529 C -21.734 52.809 -21.864 52.039 -21.864 51.239 C -21.864 50.439 -21.724 49.669 -21.484 48.949 L -21.484 45.859 L -25.464 45.859 C -26.284 47.479 -26.754 49.299 -26.754 51.239 C -26.754 53.179 -26.284 54.999 -25.464 56.619 L -21.484 53.529 Z"/>
    <path fill="#EA4335" d="M -14.754 43.989 C -12.984 43.989 -11.404 44.599 -10.154 45.789 L -6.734 42.369 C -8.804 40.429 -11.514 39.239 -14.754 39.239 C -19.444 39.239 -23.494 41.939 -25.464 45.859 L -21.484 48.949 C -20.534 46.099 -17.884 43.989 -14.754 43.989 Z"/>
  </g>
</svg>
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Google', 'registrationmagic-addon'); ?></div>
                </a>
            </div>
            
            <div class="rm-grid-icon difl">  
                <a href="<?php echo admin_url('admin.php?page=rm_login_integrations&type=linked'); ?>" class="rm_fd_link">  
                    <div class="rm-grid-icon-area rm-grid-icon-linkein dbfl">
                      <i class="fa fa-linkedin" aria-hidden="true"></i>
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Linked In', 'registrationmagic-addon'); ?></div>

                </a>
            </div> 
        </div>
        <!-- Integrate ends here -->
        

        <!-- Analyze section -->
        <div class="rm-grid-section dbfl" id="rm-login_analytics-section">
            <div class="rm-grid-section-title dbfl">
                <?php _e('Analyze', 'registrationmagic-addon'); ?>
            </div>

            <div class="rm-grid-icon difl">  
                <a href="?page=rm_login_analytics" class="rm_fd_link">  
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>form-analytics.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Login Analytics', 'registrationmagic-addon'); ?></div>
                </a>
            </div> 
            
            <div class="rm-grid-icon difl">  
                <a href="?page=rm_login_retention" class="rm_fd_link">  
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>rm-logs-retention.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('Logs Retention', 'registrationmagic-addon'); ?></div>
                </a>
            </div>
            
            <div class="rm-grid-icon difl">
                <a href="javascript:void(0)" class="rm_fd_link rm_timeline_popup_link" data-publish_type="timelinepopup">
                    <div class="rm-grid-icon-area dbfl">
                        <img class="rm-grid-icon dibfl" src="<?php echo RM_IMG_URL; ?>login-timeline.png">
                    </div>
                    <div class="rm-grid-icon-label dbfl"><?php _e('User Login Timeline', 'registrationmagic-addon'); ?></div>
                </a>
            </div>
        </div>    
    </div>
    
    <div class="rm-grid-sidebar-1 difl">
        <div class="rm-grid-section-cards dbfl">        
            <?php
            if($data->login_count == 0):
                ?>
            <div class="rm-grid-sidebar-card dbfl">
                <div class='rmnotice-container'><div class="rmnotice-container"><div class="rm-counter-box">0</div><div class="rm-counter-label"><?php echo RM_UI_Strings::get('LABEL_REGISTRATIONS'); ?></div></div></div>  
            </div>
                <?php
            endif;
            foreach ($data->login_log as $login_detail):
                ?>
                <div class="rm-grid-sidebar-card dbfl">
                    <a href="javascript:void(0)" class="fd_sub_link">
                    <div class="rm-grid-card-profile-image dbfl">
                        <?php echo get_avatar($login_detail->email)?get_avatar($login_detail->email):'<img src="'.RM_IMG_URL.'default_person.png">'; ?>
                    </div>
                    <div class="rm-grid-card-content difl">
                        <?php $user = get_user_by( 'email', $login_detail->email ); ?>
                        <div class="dbfl"><?php echo ($user)?$user->display_name:$login_detail->email; ?></div>
                        <div class="rm-grid-card-content-subtext dbfl"><?php echo date('F d Y @ g:i a',strtotime($login_detail->time)) ?></div></div>
                    </a>
                </div>
                <?php
            endforeach;
            ?>
            <div class="rm-grid-quick-tasks dbfl">
                <div class="rm-grid-sidebar-row dbfl">
                    <div class="rm-grid-sidebar-row-label difl">
                        <a class="<?php echo $data->login_count ? '' : 'rm_deactivated'?>" href="?page=rm_login_analytics"><?php echo RM_UI_Strings::get('FD_LABEL_VIEW_ALL'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="rm-grid-sidebar-2 difl">
        <div class="rm-grid-section dbfl">
            <div class="rm-grid-section-title dbfl">
                <?php echo RM_UI_Strings::get('FD_LABEL_STATUS'); ?>
                <span class="rm-grid-section-toggle rm-collapsible"></span>
            </div>
            <div class="rm-grid-sidebar-row dbfl">
                <div class="rm-grid-sidebar-row-icon difl" id="rm-sidebar-sc-icon">
                    <img src="<?php echo RM_IMG_URL; ?>shortcode.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl"><?php echo RM_UI_Strings::get('FD_LABEL_FORM_SHORTCODE'); ?>:</div>
                <div class="rm-grid-sidebar-row-value difl"><span id="rmformshortcode">[RM_Login]</span><a href="javascript:void(0)" onclick="rm_copy_to_clipboard(document.getElementById('rmformshortcode'))" id="rm-copy-sc"><?php echo RM_UI_Strings::get('FD_LABEL_COPY'); ?></a>
                    <div style="display:none" id="rm_msg_copied_to_clipboard"><?php _e('Copied to clipboard', 'registrationmagic-addon'); ?></div><div style="display:none" id="rm_msg_not_copied_to_clipboard"><?php _e('Could not be copied. Please try manually.', 'registrationmagic-addon'); ?></div></div>
            </div>
        </div>

        <div class="rm-grid-section dbfl">
            <div class="rm-grid-section-title dbfl">
                <?php echo RM_UI_Strings::get('FD_LABEL_CONTENT'); ?>
                <span class="rm-grid-section-toggle rm-collapsible"></span>
            </div>
            <div class="rm-grid-sidebar-row dbfl">
                <div class="rm-grid-sidebar-row-icon difl">
                    <img src="<?php echo RM_IMG_URL; ?>field.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl" id="rm-sidebar-fields"><?php echo RM_UI_Strings::get('FD_LABEL_F_FIELDS'); ?>:</div>
                <div class="rm-grid-sidebar-row-value difl"><?php echo isset($data->field_count)?$data->field_count:''; ?></div>
            </div>
            <div class="rm-grid-sidebar-row dbfl">
                <div class="rm-grid-sidebar-row-icon difl">
                    <img src="<?php echo RM_IMG_URL; ?>submit.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl" id="rm-sidebar-add-submit"><?php echo RM_UI_Strings::get('FD_FORM_SUBMIT_BTN_LABEL'); ?>:</div>
               <div class="rm-grid-sidebar-row-value difl"><div class="difl" id="rm-submit-label"><?php echo isset($data->buttons->login_btn) ? $data->buttons->login_btn : 'Submit'; ?></div><a href='javascript:;' onclick='edit_label()' ><?php echo RM_UI_Strings::get('LABEL_FIELD_ICON_CHANGE'); ?></a></div>
                <div id="rm-submit-label-textbox" style="display:none"><input type="text" id="submit_label_textbox"/><div><input type="button" value ="<?php _e("Save",'registrationmagic-addon'); ?>" onclick="save_submit_label()"><input type="button" value ="<?php _e("Cancel",'registrationmagic-addon') ?>" onclick="cancel_edit_label()"></div></div>
            </div>
        </div>
        <div class="rm-grid-section dbfl">
            <div class="rm-grid-section-title dbfl">
                <?php echo RM_UI_Strings::get('FD_LABEL_STATS'); ?>
                <span class="rm-grid-section-toggle rm-collapsible"></span>
            </div>
            <div class="rm-grid-sidebar-row dbfl">
                <div class="rm-grid-sidebar-row-icon difl">
                    <img src="<?php echo RM_IMG_URL; ?>submissions.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl" id="rm-sidebar-submissions"><?php echo RM_UI_Strings::get('LABEL_RECORDS'); ?>:</div>
                <div class="rm-grid-sidebar-row-value difl"><?php echo isset($data->login_count)?$data->login_count:''; ?><a href="javascript:void(0)" class="<?php echo $data->login_count ? '' : 'rm_deactivated'; ?>" onclick="jQuery.rm_do_action('rm_fd_action_form', 'rm_login_log_export')"><?php echo RM_UI_Strings::get('FD_DOWNLOAD_REGISTRATIONS'); ?></a></div>
            </div>

            <div class="rm-grid-sidebar-row dbfl">
                <div class="rm-grid-sidebar-row-icon difl">
                    <img src="<?php echo RM_IMG_URL; ?>conversion.png">
                </div>
                <div class="rm-grid-sidebar-row-label difl" id="rm-sidebar-conversion"><?php echo RM_UI_Strings::get('LABEL_SUCCESS_RATE'); ?>:</div>
                <div class="rm-grid-sidebar-row-value difl"><?php echo isset($data->success_rate)?$data->success_rate:0; ?>%</div>
            </div>

            <div class="rm-grid-quick-tasks dbfl">
                <div class="rm-grid-sidebar-row dbfl">
                    <div class="rm-grid-sidebar-row-label difl">
                        <a id="rm-sidebar-reset" href="javascript:void(0)" onclick="jQuery.rm_do_action_with_alert('<?php _e('You are going to delete all stats for login form. Do you want to proceed?','registrationmagic-addon'); ?>', 'rm_fd_action_form', 'rm_login_log_reset')"><?php echo RM_UI_Strings::get('LABEL_RESET'); ?></a>
                    </div>
                </div>
            </div>
        </div>

        

    </div>
    
    
</div>

<!-- Form Publish Pop-up -->
    <div class="rmagic rm-hide-version-number">
    <div id="rm_form_publish_popup" class="rm-modal-view" style="display: none;">
        <div class="rm-modal-overlay"></div>
        <div class="rm-modal-wrap rm-publish-form-popup">

            <div class="rm-modal-titlebar rm-new-form-popup-header">
                <div class="rm-modal-title">
                    <?php _e('Publish', 'registrationmagic-addon'); ?>
                </div>
                <span class="rm-modal-close">&times;</span>
            </div>
            <div class="rm-modal-container">
                <?php $form_id_to_publish = 'login_form'; ?>
                <?php include_once RM_ADMIN_DIR . 'views/template_rm_formflow_publish.php'; ?>
            </div>
        </div>

    </div>
    </div>
<!-- End Form Publish Pop-up -->

<!-- Timeline Pop-up -->
    <div class="rmagic rm-hide-version-number">
    <div id="rm_timeline_popup" class="rm-modal-view" style="display: none;">
        <div class="rm-modal-overlay"></div>
        <div class="rm-modal-wrap rm-publish-form-popup">

            <div class="rm-modal-titlebar rm-new-form-popup-header">
                <div class="rm-modal-title">
                    <?php _e('User Login timeline', 'registrationmagic-addon'); ?>
                </div>
                <span class="rm-modal-close">&times;</span>
            </div>
            <div class="rm-modal-container">
                <?php include_once RM_ADMIN_DIR . 'views/template_rm_formflow_timeline.php'; ?>
            </div>
        </div>

    </div>
    </div>
<!-- End Timeline Pop-up -->
<?php
            wp_enqueue_script('jquery-ui-tooltip',array('jquery'));
?>
<pre class='rm-pre-wrapper-for-script-tags'><script>
    function edit_label(){
        jQuery('#rm-submit-label-textbox').show();
    }
    
    function cancel_edit_label(){
        jQuery('#submit_label_textbox').val('');
        jQuery('#rm-submit-label-textbox').hide();
    }
    
    function save_submit_label(){
        var data = {
                        'register_btn_label': '<?php echo $data->buttons->register_btn ?>',
                        'login_btn_label': jQuery("#submit_label_textbox").val().trim(),
                        'btn_align': '<?php echo $data->buttons->align ?>',
                        'display_register': '<?php echo $data->buttons->display_register ?>'
                    };

        var data = {
                        'action': 'rm_update_login_button',
                        'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>',
                        'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>',
                        'data': data,
                    };
        jQuery.post(ajaxurl, data, function (response) {
            jQuery('#rm-submit-label').text(jQuery("#submit_label_textbox").val().trim());
            jQuery('#rm-submit-label-textbox').hide();
        });
    }
    
    jQuery(function () {
        jQuery(document).tooltip({
            content: function () {
                return jQuery(this).prop('title');
            },
            show: null, 
            close: function (event, ui) {
                ui.tooltip.hover(

                function () {
                    jQuery(this).stop(true).fadeTo(400, 1);
                },

                function () {
                    jQuery(this).fadeOut("400", function () {
                       jQuery(this).remove();
                    })
                });
            }
        });
    });   
</script></pre>

<?php
/* * ****************************************************************
 * *************     Chart drawing - Line Chart        **************
 * **************************************************************** */
$show_chart=0;
$date_labels= array();
$success_data= array();
$failure_data= array();
foreach ($data->day_wise_stat as $date => $per_day) {
    array_push($date_labels,$date);
    array_push($success_data,$per_day->success);
    array_push($failure_data,$per_day->fail);
    if(empty($show_chart) && (!empty($per_day->success) || !empty($per_day->fail))){
        $show_chart=1;
    }
}
$date_labels= json_encode($date_labels);
$success_data= json_encode($success_data);
$failure_data= json_encode($failure_data);
?>
<pre class='rm-pre-wrapper-for-script-tags'><script>
    function drawTimewiseStat()
    {
        if('<?php echo $show_chart; ?>'==0){
            jQuery("#rm_subs_over_time_chart_div,#rm_tour_timewise_stats").remove();
            return;
        }
        
       var data= {
                    labels: <?php echo $date_labels; ?>,
                    datasets:[{
                                label: 'Login Success',
                                data: <?php echo $success_data; ?>,
                                fill: false,
                                borderColor: 'rgb(53,167,227)',
                                backgroundColor: 'rgb(53,167,227)'
                    },
                    {
                                label: 'Login Failures',
                                data: <?php echo $failure_data; ?>,
                                fill: false,
                                borderColor: 'rgb(72,84,104)',
                                backgroundColor: 'rgb(72,84,104)'
                    }]
        }
        var ctx = document.getElementById('rm_subs_over_time_chart_div');
       // ctx.height = 5000;
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {}
        });
    }
</script></pre>

<script>
    jQuery(document).ready(function(){
        jQuery(".rm_publish_popup_link").each(function(){
            jQuery(this).click(function(){
                rm_set_publish_popup('login_form',jQuery(this).data("publish_type"));
                jQuery("#rm_form_publish_popup").show();
            });
            
        });
        
        jQuery(".rm_timeline_popup_link").each(function(){
            jQuery(this).click(function(){
                rm_set_publish_popup('login_form',jQuery(this).data("publish_type"));
                jQuery("#rm_timeline_popup").show();
            });
        });
        
        jQuery('.rm-modal-close, .rm-modal-overlay').click(function () {
            jQuery(this).parents('.rm-modal-view').hide();
        });
    });
</script>    

<!-- action form to execute rm_slug_actions -->
<form style="display:none" method="post" action="" id="rm_fd_action_form">
    <input type="hidden" name="action" value="" id="rm_slug_input_field">
</form>

