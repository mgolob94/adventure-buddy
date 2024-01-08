<?php

if ( ! function_exists( 'qode_tours_tour_item_meta_box_functions' ) ) {
	function qode_tours_tour_item_meta_box_functions( $post_types ) {
		$post_types[] = 'tour-item';

		return $post_types;
	}
	
	add_filter( 'bridge_qode_filter_meta_box_post_types_save', 'qode_tours_tour_item_meta_box_functions' );
	add_filter( 'bridge_qode_filter_meta_box_post_types_remove', 'qode_tours_tour_item_meta_box_functions' );
}

if ( ! function_exists( 'qode_tours_tour_item_scope_meta_box_functions' ) ) {
	function qode_tours_tour_item_scope_meta_box_functions( $post_types ) {
		$post_types[] = 'tour-item';
		
		return $post_types;
	}
	add_filter('bridge_qode_filter_general_scope_post_types', 'qode_tours_tour_item_scope_meta_box_functions');
	add_filter('bridge_qode_filter_header_scope_post_types', 'qode_tours_tour_item_scope_meta_box_functions');
	add_filter('bridge_qode_filter_title_scope_post_types', 'qode_tours_tour_item_scope_meta_box_functions');
	add_filter('bridge_qode_filter_sidebar_scope_post_types', 'qode_tours_tour_item_scope_meta_box_functions');
}

if ( ! function_exists( 'qode_tours_tour_item_enqueue_meta_box_styles' ) ) {
	function qode_tours_tour_item_enqueue_meta_box_styles() {
		global $post;
		
		if ( $post->post_type == 'tour-item' ) {
			wp_enqueue_style( 'qode-jquery-ui', get_template_directory_uri() . '/framework/admin/assets/css/jquery-ui/jquery-ui.css' );
		}
	}
	
	add_action( 'qode_enqueue_meta_box_styles', 'qode_tours_tour_item_enqueue_meta_box_styles' );
}

if ( ! function_exists( 'qode_tours_register_tour_item_cpt' ) ) {
	function qode_tours_register_tour_item_cpt( $cpt_class_name ) {
		$cpt_class = array(
			'QodeTours\CPT\Tours\ToursRegister'
		);
		
		$cpt_class_name = array_merge( $cpt_class_name, $cpt_class );
		
		return $cpt_class_name;
	}
	
	add_filter( 'qode_tours_filter_register_custom_post_types', 'qode_tours_register_tour_item_cpt' );
}

if ( ! function_exists( 'qode_tours_add_tour_item_to_search_types' ) ) {
	function qode_tours_add_tour_item_to_search_types( $post_types ) {
		$post_types['tour-item'] = esc_html__('Tour Item','qode-tours');
		
		return $post_types;
	}
	
	add_filter( 'qode_search_post_type_widget_params_post_type', 'qode_tours_add_tour_item_to_search_types' );
}


if ( ! function_exists( 'qode_tours_extend_rating_posts_types' ) ) {
    function qode_tours_extend_rating_posts_types($post_types) {
        $post_types[] = 'tour-item';

        return $post_types;
    }

    add_filter( 'bridge_core_filter_rating_post_types', 'qode_tours_extend_rating_posts_types' );
}

// Load tours shortcodes
if(!function_exists('qode_tours_include_tours_shortcodes_file')) {
    /**
     * Loades all shortcodes by going through all folders that are placed directly in shortcodes folder
     */
    function qode_tours_include_tours_shortcodes_file() {
        foreach(glob(QODE_TOURS_CPT_PATH.'/tours/shortcodes/*/load.php') as $shortcode_load) {
            include_once $shortcode_load;
        }
    }

    add_action('qode_tours_action_include_shortcodes_file', 'qode_tours_include_tours_shortcodes_file');
}

if(!function_exists('qode_tours_get_tours_categories')) {
	/**
	 * Get Tour Categories
	 * @return array
	 */
	function qode_tours_get_tours_categories() {
		$tour_categories = array();
		
		$cats = get_terms(array(
			'taxonomy' => 'tour-category'
		));
		
		foreach($cats as $cat) {
			$tour_categories[$cat->slug] = $cat->name;
		}

		return $tour_categories;
	}
}

if(!function_exists('qode_tours_get_tours_categories_vc')) {
	/**
	 * Function that returns array of tours categories formatted for Visual Composer
	 *
	 * @return array array of tours categories where key is term title and value is term slug
	 *
	 * @see qode_tours_get_tours_categories
	 */

	function qode_tours_get_tours_categories_vc() {

		return array_flip(qode_tours_get_tours_categories());
	}
}

if(!function_exists('qode_tours_get_tour_attributes')) {
	/**
	 * Return Tour Attribute Custom Post Type associative array where key is post id and value is post title.
	 *
	 * return array
	 */
	function qode_tours_get_tour_attributes() {
		$tour_attributes = array();
		
		$terms = get_terms(array(
			'taxonomy' => 'tour-attribute',
			'hide_empty' => false,
			)
		);

		foreach($terms as $term) {
			$tour_attributes[$term->slug] = $term->name;
		}

		return $tour_attributes;
	}
}

if(!function_exists('qode_tours_get_single_tour_item')) {
	/**
	 * Loads single tour-item template
	 *
	 */
	function qode_tours_get_single_tour_item() {
		$params = array(
			'holder_class'  => 'qode-tour-item-single-holder clearfix',
			'tour_sections' => qode_tours_check_tour_sections()
		);

		echo qode_tours_get_tour_module_template_part('single/holder', 'tours', 'templates', '', $params);
	}
}

if(!function_exists('qode_tours_get_tour_info_part')) {
	/**
	 * @param $part
	 *
	 * @return bool
	 */
	function qode_tours_get_tour_info_part($part) {
		if(empty($part)) {
			return false;
		}

		echo qode_tours_get_tour_module_template_part($part, 'tours', 'templates', '', array());
	}
}

if(!function_exists('qode_tours_check_tour_sections')) {
	/**
	 * check if tour item sections are enabled
	 *
	 */
	function qode_tours_check_tour_sections() {

		$sections_array = array(
			'qode_tours_show_info_section',
			'qode_tours_show_tour_plan_section',
			'qode_tours_show_location_section',
			'qode_tours_show_gallery_section',
			'qode_tours_show_review_section',
			'qode_tours_show_custom_section_1',
			'qode_tours_show_custom_section_2',
		);
		$return_array   = array();

		foreach($sections_array as $section) {
			$section_key                         = str_replace('qode_tours_', '', $section);
			$return_array[$section_key]['value'] = get_post_meta(get_the_ID(), $section, true);

			switch($section_key) {
				case 'show_info_section' :
					$return_array[$section_key]['icon']  = 'dripicons-preview';
					$return_array[$section_key]['title'] = esc_html__('Information','qode-tours');
					$return_array[$section_key]['id']    = 'tour-item-info-id';
					break;
				case 'show_tour_plan_section' :
					$return_array[$section_key]['icon']  = 'dripicons-map';
					$return_array[$section_key]['title'] = esc_html__('Tour Plan','qode-tours');
					$return_array[$section_key]['id']    = 'tour-item-plan-id';
					break;
				case 'show_location_section' :
					$return_array[$section_key]['icon']  = 'dripicons-location';
					$return_array[$section_key]['title'] = esc_html__('Location','qode-tours');
					$return_array[$section_key]['id']    = 'tour-item-location-id';
					break;
				case 'show_gallery_section' :
					$return_array[$section_key]['icon']  = 'dripicons-photo-group';
					$return_array[$section_key]['title'] = esc_html__('Gallery','qode-tours');
					$return_array[$section_key]['id']    = 'tour-item-gallery-id';
					break;
				case 'show_review_section' :
					$return_array[$section_key]['icon']  = 'dripicons-message';
					$return_array[$section_key]['title'] = esc_html__('Reviews','qode-tours');
					$return_array[$section_key]['id']    = 'tour-item-review-id';
					break;
				case 'show_custom_section_1' :

					$custom_section1_title = (get_post_meta(get_the_ID(), 'qode_tours_custom_section1_title', true) != '') ? get_post_meta(get_the_ID(), 'qode_tours_custom_section1_title', true) : esc_html__('Custom Section 1', 'qode-tours');
					$return_array[$section_key]['icon']  = 'icon_book';
					$return_array[$section_key]['title'] = $custom_section1_title;
					$return_array[$section_key]['id']    = 'tour-item-custom1-id';
					break;
				case 'show_custom_section_2' :
					$custom_section2_title = (get_post_meta(get_the_ID(), 'qode_tours_custom_section2_title', true) != '') ? get_post_meta(get_the_ID(), 'qode_tours_custom_section2_title', true) : esc_html__('Custom Section 2', 'qode-tours');
					$return_array[$section_key]['icon']  = 'icon_book';
					$return_array[$section_key]['title'] =  $custom_section2_title;
					$return_array[$section_key]['id']    = 'tour-item-custom2-id';
					break;
			}
		}

		return $return_array;
	}
}

if (!function_exists('qode_tours_booking_form_bg_img')){
	function qode_tours_booking_form_bg_img(){
		$image = bridge_qode_options()->getOptionValue('booking_form_bg_image');
		if(!empty($image)){
			echo "style='background-image: url(" . $image . ")';";
		}
	}
}

if(!function_exists('qode_tours_get_current_post_comments')){

	function qode_tours_get_current_post_comments($post_id, $order_by = 'comment_date_gmt' , $order = 'desc'){

		$meta_key  = '';
		if($order_by === 'rating'){
			$order_by = 'meta_value';
			$meta_key  = 'qode_rating';
		}elseif($order_by === 'date'){
			$order_by = 'comment_date_gmt';
		};

		$comment_args = array(
			'post_id' => $post_id,
			'status' => 'approve',
			'orderby' => $order_by,
			'meta_key'  => $meta_key,
			'order' => $order
		);
		if ( is_user_logged_in() ) {
			$comment_args['include_unapproved'] = get_current_user_id();
		} else {
			$commenter = wp_get_current_commenter();
			if ( $commenter['comment_author_email'] ) {
				$comment_args['include_unapproved'] = $commenter['comment_author_email'];
			}
		}

		$comments  = get_comments($comment_args);
		return $comments;

	}
}

if ( ! function_exists( 'qode_tours_extend_rating_posts_types' ) ) {
	function qode_tours_extend_rating_posts_types($post_types) {
		$post_types[] = 'qode-tours';

		return $post_types;
	}

	add_filter( 'bridge_core_filter_rating_post_types', 'qode_tours_extend_rating_posts_types' );
}

