<?php
if (!defined('WPINC')) {
    die('Closed');
}

$form->addElement(new Element_Textbox("<b>" . RM_UI_Strings::get('LABEL_USERNAME'). "</b>:", "username", array("required" => "1","placeholder"=>RM_UI_Strings::get('LABEL_USERNAME'))));

/*
 * Skip password field if auto generation is on
 */
if(!$is_auto_generate){
    $form->addElement(new Element_Password("<b>" . RM_UI_Strings::get('LABEL_PASSWORD') . "</b>:", "password",array("required"=>1, "longDesc"=>RM_UI_Strings::get('HELP_PASSWORD_MIN_LENGTH'),"minLength"=>7, "validation" => new Validation_RegExp("/.{7,}/", __('Error: The %element% must be atleast 7 characters long.','registrationmagic-addon')))));

}
?>