<?php

require_once 'const.php';
require_once 'helpers.php';

//load database setup
require_once 'database-setup/load.php';

//load post-types additional
require_once 'post-types/load.php';

//load lib
require_once 'lib/post-type-interface.php';
require_once 'lib/shortcode-interface.php';
require_once 'lib/shortcodes-functions.php';

//load payments
require_once 'payment/load.php';

//load post-post-types
require_once 'post-types/post-types-functions.php';
require_once 'post-types/post-types-register.php'; //this has to be loaded last

//load shortcodes inteface
require_once 'lib/shortcode-loader.php';

//load admin
require_once 'admin/load.php';

//load custom styles
if(!function_exists('qode_tours_load_custom_styles')) {
	function qode_tours_load_custom_styles() {
		require_once 'assets/custom-styles/tours.php';
	}
	add_action('after_setup_theme','qode_tours_load_custom_styles');
}

if( ! function_exists('qode_tours_add_elementor_widget_categories') ) {
	function qode_tours_add_elementor_widget_categories($elements_manager) {
		
		$elements_manager->add_category(
			'qode-tours',
			[
				'title' => esc_html__('Qode Tours', 'qode-tours'),
				'icon' => 'fa fa-plug',
			]
		);
		
	}
	
	add_action('elementor/elements/categories_registered', 'qode_tours_add_elementor_widget_categories');
};