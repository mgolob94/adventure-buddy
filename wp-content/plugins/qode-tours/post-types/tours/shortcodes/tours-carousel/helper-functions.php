<?php
if(!function_exists('qode_tours_tours_carousel_shortcode_helper')) {
    function qode_tours_tours_carousel_shortcode_helper($shortcodes_class_name) {
        $shortcodes = array(
            'QodeTours\CPT\Tours\Shortcodes\ToursCarousel'
        );

        $shortcodes_class_name = array_merge($shortcodes_class_name, $shortcodes);

        return $shortcodes_class_name;
    }

    add_filter('qode_tours_filter_add_vc_shortcode', 'qode_tours_tours_carousel_shortcode_helper');
}

if( !function_exists('qode_tours_set_tours_carousel_icon_class_name_for_vc_shortcodes') ) {
    /**
     * Function that set custom icon class name for property list shortcode to set our icon for Visual Composer shortcodes panel
     */
    function qode_tours_set_tours_carousel_icon_class_name_for_vc_shortcodes($shortcodes_icon_class_array) {
        $shortcodes_icon_class_array[] = '.icon-wpb-tours-carousel';

        return $shortcodes_icon_class_array;
    }

    add_filter('qode_tours_filter_add_vc_shortcodes_custom_icon_class', 'qode_tours_set_tours_carousel_icon_class_name_for_vc_shortcodes');
}

if(!function_exists('qode_tours_include_elementor_tours_carousel_shortcode')) {
	function qode_tours_include_elementor_tours_carousel_shortcode() {
		include_once QODE_TOURS_CPT_PATH.'/tours/shortcodes/tours-carousel/elementor-tours-carousel.php';
	}
	
	add_action('bridge_core_load_elementor_shortcodes_from_plugins', 'qode_tours_include_elementor_tours_carousel_shortcode');
}