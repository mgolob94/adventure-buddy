<?php
if (!defined('WPINC')) {
    die('Closed');
}

$settings = new RM_Options;
?>
<link rel="stylesheet" type="text/css" href="<?php echo RM_BASE_URL . 'admin/css/'; ?>style_rm_formflow.css">
<link rel="stylesheet" type="text/css" href="<?php echo RM_ADDON_BASE_URL . 'admin/css/'; ?>style_rm_formflow.css">
<script type="text/javascript" src="<?php echo RM_BASE_URL . 'admin/js/'; ?>script_rm_formflow.js"></script>

    <div  class="rm-grid difl">
        <div class="rm-grid-section dbfl rm_publish_section" id="rm_publish_timelinepopup">
            <div class="rm-directory-container dbfl">
                <div class="rm-publish-directory-col rm-pd-col-left rm-difl ">
                    <?php echo sprintf(__("You can check individual user's login timeline by visiting the user's page inside User Manager. <a target='_blank' class='rm-more' href='%s'>More Info</a><br><br><a target='_blank' class='rm-more' href='%s'>Visit User Manager Now</a>", 'registrationmagic-addon'),'https://registrationmagic.com/wordpress-user-login-plugin-guide/#user-manager','?page=rm_user_manage'); ?>
                </div>
                <div class="rm-publish-directory-col rm-difl">  
                    <div class="rm-section-user-manager">
                        <img src="<?php echo RM_IMG_URL . "user-manager.png"; ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

