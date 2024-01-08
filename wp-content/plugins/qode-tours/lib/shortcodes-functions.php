<?php

if(!function_exists('qode_tours_include_shortcodes_file')) {
	/**
	 * Loades all shortcodes by going through all folders that are placed directly in shortcodes folder
	 */
	function qode_tours_include_shortcodes_file() {
		do_action('qode_tours_action_include_shortcodes_file');
	}
	
	add_action('init', 'qode_tours_include_shortcodes_file', 6); // permission 6 is set to be before vc_before_init hook that has permission 9
}

if(!function_exists('qode_tours_load_shortcodes')) {
	function qode_tours_load_shortcodes() {
		include_once QODE_TOURS_ABS_PATH.'/lib/shortcode-loader.php';
		
		QodeTours\Lib\ShortcodeLoader::getInstance()->load();
	}
	
	add_action('init', 'qode_tours_load_shortcodes', 7); // permission 7 is set to be before vc_before_init hook that has permission 9 and after qode_tours_include_shortcodes_file hook
}
