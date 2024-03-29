<?php
if (!defined('WPINC')) {
    die('Closed');
}
if(defined('REGMAGIC_ADDON')) {
    include_once(RM_ADDON_ADMIN_DIR . 'views/template_rm_form_cstatus_manager.php');
} else { ?>
<div class="rmagic">
    <div class="operationsbar">
        <!-- <div class="icons">
            <img alt="" src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'images/supporticon.png'; ?>">
        </div> -->
        <div class="rmtitle"><?php _e('Custom Status', 'custom-registration-form-builder-with-submission-manager'); ?></div>
        <div class="nav">
            <ul>
                <li onclick="window.history.back()"><a href="javascript:void(0)"><?php echo wp_kses_post(RM_UI_Strings::get("LABEL_BACK")); ?></a></li>
            </ul>
        </div>
    </div>
    <?php 
    $rm_promo_banner_title = __('Unlock the power of Custom Status by upgrading','custom-registration-form-builder-with-submission-manager');
    include RM_ADMIN_DIR.'views/template_rm_promo_banner_bottom.php';
    ?>
</div>
<?php } ?>