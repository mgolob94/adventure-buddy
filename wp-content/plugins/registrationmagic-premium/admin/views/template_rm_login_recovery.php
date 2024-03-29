<?php
if (!defined('WPINC')) {
    die('Closed');
}
$params= $data->params; ?>
<div class="rmagic">
    <!--Dialogue Box Starts-->
    <div class="rmcontent">
        <div class="rmheader"><?php echo _e('Password Recovery', 'registrationmagic-addon'); ?></div>
        <?php
        $form = new RM_PFBC_Form("login-recovery");

        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));
        
        $form->addElement(new Element_Checkbox("<b>" .__('Display Password recovery link?', 'registrationmagic-addon')."</b>", "en_pwd_recovery", array(1 => ""), array("class" => "rm-role-based rm-static-field rm_input_type", "value" => isset($params['en_pwd_recovery']) ? $params['en_pwd_recovery']:0, "longdesc" => __('Display a link below the login button that allows users to initiate a password recovery process.', 'registrationmagic-addon'))));
             
            $form->addElement(new Element_HTML('<div id="rm_password_recovery" style="display:none;" class="childfieldsrow">'));   
                $form->addElement(new Element_Textbox("<b>" . __('Link Anchor Text', 'registrationmagic-addon') . "</b>", "recovery_link_text", array("value" => $params['recovery_link_text'], "longDesc" => __('Password recovery link text.', 'registrationmagic-addon'))));
                $form->addElement(new Element_Select("<b>" .__('Password Recovery Page','registrationmagic-addon'). "</b>", "recovery_page", RM_Utilities::wp_pages_dropdown(), array("id" => "rm_recovery_page", "value" => $params['recovery_page'], "class" => "rm_static_field", "longDesc"=>__('Select the page which has [RM_password_recovery] shortcode pasted inside it. Users will be taken to this page when they click password recovery link. When you activate RegistrationMagic, a default page with shortcode is automatically created and assigned here.','registrationmagic-addon'))));
                $form->addElement(new Element_Textbox("<b>" . __('Your Email Label', 'registrationmagic-addon') . "</b>", "rec_email_label", array("value" =>$params['rec_email_label'], "longDesc" => __('Define the label for User Email field on password recovery page.', 'registrationmagic-addon'))));
                $form->addElement(new Element_Textbox("<b>" . __('Submit Email Button Label', 'registrationmagic-addon') . "</b>", "rec_btn_label", array("value" =>$params['rec_btn_label'], "longDesc" => __('Define the label for submitting User Email on password recovery page.', 'registrationmagic-addon'))));
                $form->addElement(new Element_TinyMCEWP(__('Link Sent Message','registrationmagic-addon'),$params['rec_link_sent_msg'], "rec_link_sent_msg", array('editor_class' => 'rm_TinydMCE', 'editor_height' => '100px'), array("longDesc" =>__('Define message users see when system successfully sends them email recovery password link. You can customize this email by going to Login Form Dashboard --> Email Templates','registrationmagic-addon')))); 
                $form->addElement(new Element_TinyMCEWP(__('Email not Found','registrationmagic-addon'),$params['rec_email_not_found_msg'], "rec_email_not_found_msg", array('editor_class' => 'rm_TinydMCE', 'editor_height' => '100px'), array("longDesc" =>__('Define the message user enters an email which is not associated with any user account on this site.','registrationmagic-addon')))); 
                $form->addElement(new Element_Textbox("<b>" . __('New Password Label', 'registrationmagic-addon') . "</b>", "rec_new_pass_label", array("value" =>$params['rec_new_pass_label'], "longDesc" => __('Define the label for new password field on password recovery page.', 'registrationmagic-addon'))));
                $form->addElement(new Element_Textbox("<b>" . __('Confirm Password Label', 'registrationmagic-addon') . "</b>", "rec_conf_pass_label", array("value" =>$params['rec_conf_pass_label'], "longDesc" => __('Define the label for password confirmation field on password recovery page.', 'registrationmagic-addon'))));
                $form->addElement(new Element_Textbox("<b>" . __('Button Label', 'registrationmagic-addon') . "</b>", "rec_pass_btn_label", array("value" =>$params['rec_pass_btn_label'], "longDesc" => __('Define the label of the button used for submitting new password on password recovery page.', 'registrationmagic-addon'))));
                $form->addElement(new Element_Textarea("<b>" . __('Password Match Error', 'registrationmagic-addon') . "</b>", "rec_pass_match_err", array("class" => "rm_static_field rm_field_value", "value" =>$params['rec_pass_match_err'], "longDesc"=>__('Define the error message user sees when trying to submit non matching passwords on password recovery page.','registrationmagic-addon'))));
                $form->addElement(new Element_TinyMCEWP(__('Success Message','registrationmagic-addon'),$params['rec_pas_suc_message'], "rec_pas_suc_message", array('editor_class' => 'rm_TinydMCE', 'editor_height' => '100px'), array("longDesc" =>__('Define the message users see after successfully changing their password.','registrationmagic-addon')))); 
                $form->addElement(new Element_TinyMCEWP(__('Invalid Reset Link Error','registrationmagic-addon'),$params['rec_invalid_reset_err'], "rec_invalid_reset_err", array('editor_class' => 'rm_TinydMCE', 'editor_height' => '100px'), array("longDesc" =>__('Define the message users see if they reach password recovery page by clicking a broken or incomplete link in their inbox. They will still have option to manually paste the security token and proceed with password reset.','registrationmagic-addon'))));
                $form->addElement(new Element_Textbox("<b>" . __('Token Submit Button Label', 'registrationmagic-addon') . "</b>", "rec_tok_sub_label", array("value" =>$params['rec_tok_sub_label'], "longDesc" => __('Define the label of the button used for submitting security token on password recovery page.', 'registrationmagic-addon'))));
                $form->addElement(new Element_TinyMCEWP(__('Invalid Security Token Error','registrationmagic-addon'),$params['rec_invalid_tok_err'], "rec_invalid_tok_err", array('editor_class' => 'rm_TinydMCE', 'editor_height' => '100px'), array("longDesc" =>__('Define the message users see if they reach password recovery page by clicking a broken or incomplete link in their inbox. They will still have option to manually paste the security token and proceed with password reset.','registrationmagic-addon'))));
                $form->addElement(new Element_Number("<b>" . __('Password Reset Link Expiry', 'registrationmagic-addon') . "</b>", "rec_link_expiry", array("value" =>$params['rec_link_expiry'], "longDesc" => __('Define time (in Hrs.) after which the security link stops working. For example, enter 48 if you wish the links to stop working after 2 days from the time of their generation. Use 0 to disable this feature.', 'registrationmagic-addon'))));
                $form->addElement(new Element_Textarea("<b>" . __('Link Expiry Error', 'registrationmagic-addon') . "</b>", "rec_link_exp_err", array("class" => "rm_static_field rm_field_value", "value" =>$params['rec_link_exp_err'], "longDesc"=>'')));
                $form->addElement(new Element_Checkbox("<b>" .__('Redirect Default WordPress Link?', 'registrationmagic-addon')."</b>", "rec_redirect_default", array(1 => ""), array("class" => "rm-static-field rm_input_type", "value" =>$params['rec_redirect_default'], "longdesc" => __('Redirects default WordPress password recovery link to Password Recovery Page.', 'registrationmagic-addon'))));
            $form->addElement(new Element_HTML('</div>'));
            
            $form->addElement(new Element_HTMLL('&#8592; &nbsp; '.__('Cancel','registrationmagic-addon'), '?page=rm_login_sett_manage', array('class' => 'cancel')));
            $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn", "name" => "submit")));
        $form->render();
        ?>
    </div>
</div>

<script>
    jQuery(document).ready(function(){
        jQuery("#login-recovery-element-0-0").change(function(){
            if(jQuery(this).is(':checked')){
                jQuery("#rm_password_recovery").slideDown();
            }
            else{
                 jQuery("#rm_password_recovery").slideUp();
            }
        });
        jQuery("#login-recovery-element-0-0").trigger('change');
    });
    
    
    
</script>    
