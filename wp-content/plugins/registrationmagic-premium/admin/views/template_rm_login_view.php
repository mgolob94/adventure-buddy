<?php
if (!defined('WPINC')) {
    die('Closed');
}
$params= $data->params; ?>
<?php 
    wp_enqueue_script('script_rm_logged_in_view',RM_BASE_URL . 'admin/js/logged_in_view.js',array('script_rm_angular')); 
    wp_localize_script('script_rm_logged_in_view','logged_in_params',$params);
?>
<div class="rmagic">

    <!--Dialogue Box Starts-->
    <div class="rmcontent " ng-controller="loggedInViewCtrl"  ng-app="loggedInViewApp">
        
        <div class="rmheader"><?php echo _e('Logged In View', 'registrationmagic-addon'); ?></div>
        
        <div class="rm-logged-in-view rm-dbfl">
   
           
                <div class="rm-logged-in-view-wrap">
               
 
                    <div class="rm-logged-in-lf rm-difl">
                        <img alt="" src="<?php echo RM_IMG_URL . 'default_person.png'; ?>" class="avatar avatar-96 photo" height="96" width="96">            </div>

                    <div class="rm-logged-in-rf rm-difl">

                        <div class="rm-logged-welcome">
                            <!-- User greeting message -->
                            <span class="rm-greetings-text rm-dbfl " ng-if="displayGreetings">{{greetingText}}</span>

                            <!-- Show user name -->
                            <span class="rm-user-display-name rm-dbfl" ng-if="displayUsername"><?php _e('Full Name','registrationmagic-addon'); ?></span>

                        </div>


                        <!-- User bio description -->
                         <!-- <div><?php _e('This is bio description.','registrationmagic-addon') ?></div>-->
                   <!-- Login custom message -->
                    <div class="rm_display_custom_msg rm-dbfl" ng-if="displayCustomMessage">{{customMessage}}</div>
                    </div> 
                   
                
                    
                    <div class="rm-logged-in-account-links rm-dbfl" style="border-color:#{{barColor}}">
                        <!-- My Account -->
                        <span class="rm_display_account rm-difl" ng-if="displayAccountLink"><a href="#">{{accountLinkText}}</a></span>

                        <!-- Logout -->  
                        <span class="rm_display_logout rm-difr" ng-if="displayLogoutLink">
                            <a href="#">{{logoutText}}</a>
                        </span>
                    </div>
                    
                </div>
            
            <div class="rmrow"> <div class="rmnotice"><?php _e('The color of hyperlink anchor text for My Account and Logout will appear based on your theme on the front end. Colors displayed here are only for representation.','registrationmagic-addon') ?></div></div>
     
        </div>
        
        <?php
        $form = new RM_PFBC_Form("login-view");

        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));
        
        $form->addElement(new Element_Checkbox("<b>" . __('Display user Avatar', 'registrationmagic-addon') . "</b>", "display_user_avatar", array(1 => ""), array("class" => "rm-static-field rm_input_type","ng-model"=>"displayAvatar", "value" => isset($params['display_user_avatar']) ? $params['display_user_avatar'] : 0, "longdesc" => RM_UI_Strings::get('LABEL_DISPLAY_AVATAR') )));
        $form->addElement(new Element_Checkbox("<b>" . __("Display user’s name", 'registrationmagic-addon') . "</b>", "display_user_name", array(1 => ""), array("class" => "rm-static-field rm_input_type","ng-model"=>"displayUsername", "value" => isset($params['display_user_name']) ? $params['display_user_name'] : 0, "longdesc" => RM_UI_Strings::get('LABEL_DISPLAY_USERNAME') )));
        
        $form->addElement(new Element_Checkbox("<b>" . __('Display greetings', 'registrationmagic-addon') . "</b>", "display_greetings", array(1 => ""), array("class" => "rm-static-field rm_input_type","ng-model"=>"displayGreetings", "value" => isset($params['display_greetings']) ? $params['display_greetings'] : 0, "longdesc" => RM_UI_Strings::get('LABEL_DISPLAY_GREETINGS') )));
            $form->addElement(new Element_HTML('<div id="rm_display_greetings" class="childfieldsrow">'));   
                $form->addElement(new Element_Textbox("<b>" . __('Greetings Text', 'registrationmagic-addon') . "</b>", "greetings_text", array("value" => $params['greetings_text'],"ng-model"=>"greetingText", "longDesc" => RM_UI_Strings::get('FIELD_GREETING_TEXT') )));
            $form->addElement(new Element_HTML('</div>'));
            
        $form->addElement(new Element_Checkbox("<b>" . __('Display custom message', 'registrationmagic-addon') . "</b>", "display_custom_msg", array(1 => ""), array("class" => "rm-static-field rm_input_type","ng-model"=>"displayCustomMessage", "value" => isset($params['display_custom_msg']) ? $params['display_custom_msg'] : 0, "longdesc" => RM_UI_Strings::get('LABEL_DISPLAY_CUSTOM_MSG') )));
            $form->addElement(new Element_HTML('<div id="rm_display_custom_msg" class="childfieldsrow">'));   
                $form->addElement(new Element_Textarea("<b>" . __('Custom message', 'registrationmagic-addon') . "</b>", "custom_msg", array("value" => $params['custom_msg'],"ng-model"=>"customMessage", "longDesc" => RM_UI_Strings::get('FIELD_CUSTOM_MSG') )));
            $form->addElement(new Element_HTML('</div>'));
            
        $form->addElement(new Element_Color(RM_UI_Strings::get('LABEL_FIELD_BAR_COLOR'), "separator_bar_color", array("id" => "rm_","ng-model"=>"barColor", "value" => $params['separator_bar_color'], "onchange" => "change_icon_fg_color(this)", "longDesc" => RM_UI_Strings::get('HELP_FIELD_BAR_COLOR'))));
            
        $form->addElement(new Element_Checkbox("<b>" . __('Display user account link', 'registrationmagic-addon') . "</b>", "display_account_link", array(1 => ""), array("class" => "rm-static-field rm_input_type","ng-model"=>"displayAccountLink", "value" => isset($params['display_account_link']) ? $params['display_account_link'] : 0, "longdesc" => RM_UI_Strings::get('HELP_ACCOUNT_TEXT') )));
            $form->addElement(new Element_HTML('<div id="rm_display_account" class="childfieldsrow">'));   
                $form->addElement(new Element_Textbox("<b>" . __('User account link text', 'registrationmagic-addon') . "</b>", "account_link_text", array("value" => $params['account_link_text'],"ng-model"=>"accountLinkText", "longDesc" => RM_UI_Strings::get('HELP_ACCOUNT_LINK') )));
            $form->addElement(new Element_HTML('</div>'));
            
        $form->addElement(new Element_Checkbox("<b>" . __('Display logout link', 'registrationmagic-addon') . "</b>", "display_logout_link", array(1 => ""), array("class" => "rm-static-field rm_input_type","ng-model"=>"displayLogoutLink", "value" => isset($params['display_logout_link']) ? $params['display_logout_link'] : 0, "longdesc" => RM_UI_Strings::get('HELP_LOGOUT_TEXT') )));
            $form->addElement(new Element_HTML('<div id="rm_display_logout" class="childfieldsrow">'));   
                $form->addElement(new Element_Textbox("<b>" . __('Logout link text', 'registrationmagic-addon') . "</b>", "logout_text", array("value" => $params['logout_text'],"ng-model"=>"logoutText", "longDesc" => RM_UI_Strings::get('HELP_LOGOUT_LINK') )));
            $form->addElement(new Element_HTML('</div>'));
            
            
            $form->addElement(new Element_HTMLL('&#8592; &nbsp; '.__('Cancel','registrationmagic-addon'), '?page=rm_login_sett_manage', array('class' => 'cancel')));
            $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn", "name" => "submit")));
        $form->render();
        ?>
    </div>
</div>

<script>
    jQuery(document).ready(function(){
        jQuery("#login-view-element-0-0").change(function(){
            if(jQuery(this).is(':checked')){
                jQuery(".rm-logged-in-lf").css('visibility','visible');
            }
            else{
                 jQuery(".rm-logged-in-lf").css('visibility','hidden');
            }
            get_checked_login();
        });
        jQuery("#login-view-element-0-0").trigger('change');
        
        jQuery("#login-view-element-1-0").change(function(){
            get_checked_login();
        });
        
        jQuery("#login-view-element-2-0").change(function(){
            if(jQuery(this).is(':checked')){
                jQuery("#rm_display_greetings").slideDown();
            }
            else{
                 jQuery("#rm_display_greetings").slideUp();
            }
            get_checked_login();
        });
        jQuery("#login-view-element-2-0").trigger('change');
        
        jQuery("#login-view-element-6-0").change(function(){
            if(jQuery(this).is(':checked')){
                jQuery("#rm_display_custom_msg").slideDown();
            }
            else{
                 jQuery("#rm_display_custom_msg").slideUp();
            }
            get_checked_login();
        });
        jQuery("#login-view-element-6-0").trigger('change');
        
        jQuery("#login-view-element-11-0").change(function(){
            if(jQuery(this).is(':checked')){
                jQuery("#rm_display_account").slideDown();
            }
            else{
                 jQuery("#rm_display_account").slideUp();
            }
            get_checked_login();
        });
        jQuery("#login-view-element-11-0").trigger('change');
        
        jQuery("#login-view-element-15-0").change(function(){
            if(jQuery(this).is(':checked')){
                jQuery("#rm_display_logout").slideDown();
            }
            else{
                 jQuery("#rm_display_logout").slideUp();
            }
            get_checked_login();
        });
        jQuery("#login-view-element-15-0").trigger('change');
        
        get_checked_login();
    });
    function get_checked_login(){
        if(jQuery('#login-view-element-0-0').is(':not(:checked)') && jQuery('#login-view-element-1-0').is(':not(:checked)')  && jQuery('#login-view-element-2-0').is(':not(:checked)')  && jQuery('#login-view-element-6-0').is(':not(:checked)')  && jQuery('#login-view-element-11-0').is(':not(:checked)')  && jQuery('#login-view-element-15-0').is(':checked')){
            jQuery('.rm-logged-in-view-wrap').addClass('rm-display-logout');
        }else{
            jQuery('.rm-logged-in-view-wrap').removeClass('rm-display-logout');
        }
    }
    
</script>
   
