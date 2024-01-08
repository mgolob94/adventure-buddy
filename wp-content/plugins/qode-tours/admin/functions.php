<?php
use QodeTours\Admin\BookingDashboard\BookingSubmenuPage;

if(!function_exists('qode_tours_load_admin_assets')) {
	function qode_tours_load_admin_assets() {
		global $post_type;

		wp_enqueue_script('qode-booking-dashboard', plugins_url('/assets/js/booking-dashboard.js', __FILE__), array(), '', true);
		wp_enqueue_style('qode-booking-dashboard', plugins_url('/assets/css/booking-dashboard.css', __FILE__), array(), '', 'all');
	}

	add_action('admin_enqueue_scripts', 'qode_tours_load_admin_assets');
}

if(!function_exists('qode_tours_init_booking_dashboard')) {

	function qode_tours_init_booking_dashboard() {
		BookingSubmenuPage::getInstance();
	}

	add_action('plugins_loaded', 'qode_tours_init_booking_dashboard');
}

if(!function_exists('qode_tours_add_ajax_url')) {

	function qode_tours_add_ajax_url() {
		wp_localize_script('qode-booking-dashboard', 'QodeToursAjaxUrl', array(
			'url' => admin_url('admin-ajax.php')
		));
	}

	add_action('admin_enqueue_scripts', 'qode_tours_add_ajax_url');
}