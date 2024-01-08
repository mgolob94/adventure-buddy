<?php

if ( ! function_exists( 'qode_tours_destinations_meta_box_functions' ) ) {
	function qode_tours_destinations_meta_box_functions( $post_types ) {
		$post_types[] = 'destinations';
		
		return $post_types;
	}
	
	add_filter( 'bridge_qode_filter_meta_box_post_types_save', 'qode_tours_destinations_meta_box_functions' );
	add_filter( 'bridge_qode_filter_meta_box_post_types_remove', 'qode_tours_destinations_meta_box_functions' );
}

if ( ! function_exists( 'qode_tours_destinations_scope_meta_box_functions' ) ) {
	function qode_tours_destinations_scope_meta_box_functions( $post_types ) {
		$post_types[] = 'destinations';
		
		return $post_types;
	}

	add_filter('bridge_qode_filter_general_scope_post_types', 'qode_tours_destinations_scope_meta_box_functions');
	add_filter('bridge_qode_filter_header_scope_post_types', 'qode_tours_destinations_scope_meta_box_functions');
	add_filter('bridge_qode_filter_title_scope_post_types', 'qode_tours_destinations_scope_meta_box_functions');
	add_filter('bridge_qode_filter_sidebar_scope_post_types', 'qode_tours_destinations_scope_meta_box_functions');
}

if ( ! function_exists( 'qode_tours_destinations_enqueue_meta_box_styles' ) ) {
	function qode_tours_destinations_enqueue_meta_box_styles() {
		global $post;
		
		if ( $post->post_type == 'destinations' ) {
			wp_enqueue_style( 'qode-jquery-ui', get_template_directory_uri() . '/framework/admin/assets/css/jquery-ui/jquery-ui.css' );
		}
	}
	
	add_action( 'qode_enqueue_meta_box_styles', 'qode_tours_destinations_enqueue_meta_box_styles' );
}

if ( ! function_exists( 'qode_tours_register_destinations_cpt' ) ) {
	function qode_tours_register_destinations_cpt( $cpt_class_name ) {
		$cpt_class = array(
			'QodeTours\CPT\Destination\DestinationsRegister'
		);
		
		$cpt_class_name = array_merge( $cpt_class_name, $cpt_class );
		
		return $cpt_class_name;
	}
	
	add_filter( 'qode_tours_filter_register_custom_post_types', 'qode_tours_register_destinations_cpt' );
}

if ( ! function_exists( 'qode_tours_add_destinations_to_search_types' ) ) {
	function qode_tours_add_destinations_to_search_types( $post_types ) {
		$post_types['destinations'] = esc_html__('Destinations','qode-tours');
		
		return $post_types;
	}
	
	add_filter( 'qode_search_post_type_widget_params_post_type', 'qode_tours_add_destinations_to_search_types' );
}

// Load destination shortcodes
if(!function_exists('qode_tours_include_destination_shortcodes_file')) {
    /**
     * Loades all shortcodes by going through all folders that are placed directly in shortcodes folder
     */
    function qode_tours_include_destination_shortcodes_file() {
        foreach(glob(QODE_TOURS_CPT_PATH.'/destinations/shortcodes/*/load.php') as $shortcode_load) {
            include_once $shortcode_load;
        }
    }

    add_action('qode_tours_action_include_shortcodes_file', 'qode_tours_include_destination_shortcodes_file');
}

if(!function_exists('qode_tours_get_destinations')) {
	/**
	 * @param bool $first_empty
	 *
	 * @return array
	 */
	function qode_tours_get_destinations($first_empty = false) {
		$destinations = array();

		if($first_empty) {
			$destinations[''] = esc_html__('Select Your Destination', 'qode-tours');
		}
		
		if(qode_tours_is_wpml_installed()) {
			global $wpdb;
			
			$lang = ICL_LANGUAGE_CODE;
			
			$sql = "SELECT p.*
					FROM {$wpdb->prefix}posts p
					LEFT JOIN {$wpdb->prefix}icl_translations icl_t ON icl_t.element_id = p.ID 
					WHERE p.post_type = 'destinations'
					AND p.post_status = 'publish'
					AND icl_t.language_code='{$lang}'";
			
			$query_results = $wpdb->get_results($sql);
			
			if($query_results) {
				global $post;
				
				foreach ($query_results as $post) {
					setup_postdata($post);
					$destinations[get_the_ID()] = get_the_title();
				}
			}
		} else {
			$args = array(
				'post_type'      => 'destinations',
				'post_status'    => 'publish',
				'posts_per_page' => -1
			);
			
			$query_results = new WP_Query($args);
			
			if($query_results->have_posts()) {
				
				while($query_results->have_posts()) {
					
					$query_results->the_post();
					
					$destinations[get_the_ID()] = get_the_title();
				}
			}
		}
		
		wp_reset_postdata();

		return $destinations;
	}
}