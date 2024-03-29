<?php
if (!defined('WPINC')) {
    die('Closed');
}

/*
 * To show all the available setting options
 */

$image_path = RM_IMG_URL;
global $rm_env_requirements;
?>

<?php if (!($rm_env_requirements & RM_REQ_EXT_CURL)){ ?>
 <div class="shortcode_notification ext_na_error_notice"><p class="rm-notice-para"><?php echo RM_UI_Strings::get('RM_ERROR_EXTENSION_CURL');?></p></div>
 <?php } 
 
 ?>
<div class="rmagic">

    <!-----Settings Area Starts----->
    
    <div class="rm-global-settings">
        <?php if(is_admin()) { ?>
        <div class ="rmnotice" style="min-height:45px;">
                  
                  <?php
                    echo __('Form specific settings can be found inside individual Form Dashboard.','registrationmagic-addon');
                   ?>
                
                </div>
        <?php }?>
        <div class="rm-settings-title"><?php _e('Global Settings', 'registrationmagic-addon'); ?></div>
        
        <div class="settings-icon-area">
            <a href="admin.php?page=rm_options_general">
                <div class="rm-settings-box">
                    <img class="rm-settings-icon" src="<?php echo $image_path; ?>general-settings.png">
                    <div class="rm-settings-description">

                    </div>
                    <div class="rm-settings-subtitle"><?php echo RM_UI_Strings::get('GLOBAL_SETTINGS_GENERAL'); ?></div>
                    <span><?php echo RM_UI_Strings::get('GLOBAL_SETTINGS_GENERAL_EXCERPT'); ?></span>
                </div></a>
            
            <a href="admin.php?page=rm_options_fab">
                <div class="rm-settings-box">
                    <img class="rm-settings-icon" src="<?php echo $image_path; ?>fab-options.png">
                    <div class="rm-settings-description">

                    </div>
                    <div class="rm-settings-subtitle"><?php echo RM_UI_Strings::get('GLOBAL_SETTINGS_FAB'); ?></div>
                    <span><?php echo RM_UI_Strings::get('GLOBAL_SETTINGS_FAB_EXCERPT'); ?></span>
                </div></a>

            <a href="admin.php?page=rm_options_security">
                <div class="rm-settings-box">
                    <img class="rm-settings-icon" src="<?php echo $image_path; ?>rm-security.png">
                    <div class="rm-settings-description">

                    </div>
                    <div class="rm-settings-subtitle"><?php echo RM_UI_Strings::get('GLOBAL_SETTINGS_SECURITY'); ?></div>
                    <span><?php echo RM_UI_Strings::get('GLOBAL_SETTINGS_SECURITY_EXCERPT'); ?></span>
                </div></a>

            <a href="admin.php?page=rm_options_user">
                <div class="rm-settings-box">
                    <img class="rm-settings-icon" src="<?php echo $image_path; ?>rm-user-accounts.png">
                    <div class="rm-settings-description">

                    </div>
                    <div class="rm-settings-subtitle"><?php echo RM_UI_Strings::get('GLOBAL_SETTINGS_USER'); ?></div>
                    <span><?php echo RM_UI_Strings::get('GLOBAL_SETTINGS_USER_EXCERPT'); ?></span>
                </div></a>

            <a href="admin.php?page=rm_options_autoresponder">
                <div class="rm-settings-box">
                    <img class="rm-settings-icon" src="<?php echo $image_path; ?>rm-email-notifications.png">
                    <div class="rm-settings-description">

                    </div>
                    <div class="rm-settings-subtitle"><?php echo RM_UI_Strings::get('GLOBAL_SETTINGS_EMAIL_NOTIFICATIONS'); ?></div>
                    <span><?php echo RM_UI_Strings::get('GLOBAL_SETTINGS_EMAIL_NOTIFICATIONS_EXCERPT'); ?></span>
                </div></a>

            <a href="admin.php?page=rm_options_thirdparty">
                <div class="rm-settings-box">
                    <img class="rm-settings-icon" src="<?php echo $image_path; ?>rm-third-party.png">
                    <div class="rm-settings-description">

                    </div>
                    <div class="rm-settings-subtitle"><?php echo RM_UI_Strings::get('GLOBAL_SETTINGS_EXTERNAL_INTEGRATIONS'); ?></div>
                    <span><?php echo RM_UI_Strings::get('GLOBAL_SETTINGS_EXTERNAL_INTEGRATIONS_EXCERPT'); ?></span>
                </div></a>

                <a href="admin.php?page=rm_options_payment">
                <div class="rm-settings-box">
                    <img class="rm-settings-icon" src="<?php echo $image_path; ?>rm-payments.png">
                    <div class="rm-settings-description">

                    </div>
                    <div class="rm-settings-subtitle"><?php echo RM_UI_Strings::get('GLOBAL_SETTINGS_PAYMENT'); ?></div>
                    <span><?php echo RM_UI_Strings::get('GLOBAL_SETTINGS_PAYMENT_EXCERPT'); ?></span>
                </div>
            </a>
            
            <a href="admin.php?page=rm_options_default_pages">
                <div class="rm-settings-box">
                    <img class="rm-settings-icon" src="<?php echo $image_path; ?>rm-default-pages.png">
                    <div class="rm-settings-description">

                    </div>
                    <div class="rm-settings-subtitle"><?php _e('Default Pages', 'registrationmagic-addon'); ?></div>
                    <span><?php _e('Map registration page, user account page etc.', 'registrationmagic-addon'); ?></span>
                </div>
            </a>
            
            <a href="admin.php?page=rm_options_user_privacy">
                <div class="rm-settings-box">
                    <img class="rm-settings-icon" src="<?php echo $image_path; ?>user-privacy.png">
                    <div class="rm-settings-description">

                    </div>
                    <div class="rm-settings-subtitle"><?php _e('Privacy', 'registrationmagic-addon'); ?></div>
                    <span><?php _e('Configure data privacy settings', 'registrationmagic-addon'); ?></span>
                </div>
            </a>
            
                <a href="admin.php?page=rm_options_advance">
                <div class="rm-settings-box">
                    <img class="rm-settings-icon" src="<?php echo $image_path; ?>rm_advance_options.png">
                    <div class="rm-settings-description">

                    </div>
                    <div class="rm-settings-subtitle">Advanced Options</div>
                    <span>Libraries and sessions management</span>
                </div></a>
            
            <a href="admin.php?page=rm_form_sett_profilegrid&gs=1">
                <div class="rm-settings-box">
                    <img class="rm-settings-icon" src="<?php echo RM_IMG_URL; ?>profilegrid.png">
                    <div class="rm-settings-description">

                    </div>
                    <div class="rm-settings-subtitle"><?php echo __('ProfileGrid', 'registrationmagic-addon'); ?></div>
                    <span><?php echo __('User Profiles, Groups, Memberships and Communities', 'registrationmagic-addon'); ?></span>
                </div>
            </a>
            <a href="admin.php?page=rm_options_admin_menu">
                <div class="rm-settings-box">
                    <img class="rm-settings-icon" src="<?php echo esc_url(RM_IMG_URL); ?>rm-admin-menu.png">
                    <div class="rm-settings-description">

                    </div>
                    <div class="rm-settings-subtitle"><?php echo __('Admin Menu', 'custom-registration-form-builder-with-submission-manager'); ?></div>
                    <span><?php echo __('Customize Admin menu items to quicken your workflow.', 'custom-registration-form-builder-with-submission-manager'); ?></span>
                </div>
            </a>
            <?php /*
            <a href="admin.php?page=rm_options_eventprime&gs=1">
                <div class="rm-settings-box">
                    <img class="rm-settings-icon" src="<?php echo $image_path; ?>event-prime-logo.png">
                    <div class="rm-settings-description">

                    </div>
                    <div class="rm-settings-subtitle"><?php _e('EventPrime', 'registrationmagic-addon') ?></div>
                    <span><?php _e('Event Registrations and Bookings', 'registrationmagic-addon'); ?></span>
                </div>
            </a> */?>
            
            <?php  echo apply_filters('rm_global_setting_manager', ''); ?>
        </div>
    </div>
</div>
