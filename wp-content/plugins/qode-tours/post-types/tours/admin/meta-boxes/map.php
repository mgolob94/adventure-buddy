<?php
if ( ! function_exists( 'qode_tours_info_section_map' ) ) {
	
	function qode_tours_info_section_map() {
		$destinations = qode_tours_get_destinations( true );
		
		$info_section_meta_box = bridge_qode_create_meta_box(
			array(
				'scope' => array( 'tour-item' ),
				'title' => esc_html__( 'Info Section', 'qode-tours' ),
				'name'  => 'tours_info_section_meta'
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_show_info_section',
				'type'          => 'yesno',
				'default_value' => 'yes',
				'label'         => esc_html__( 'Show Info Section', 'qode-tours' ),
				'parent'        => $info_section_meta_box
			)
		);
		
		$info_section_container = bridge_qode_add_admin_container_no_style(
			array(
				'type'       => 'container',
				'name'       => 'info_section_container',
				'parent'     => $info_section_meta_box,
				'dependency' => array(
					'show' => array(
						'qode_tours_show_info_section' => 'yes'
					)
				)
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_price',
				'type'          => 'text',
				'default_value' => '',
				'label'         => esc_html__( 'Price', 'qode-tours' ),
				'parent'        => $info_section_container
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_discount_price',
				'type'          => 'text',
				'default_value' => '',
				'label'         => esc_html__( 'Discount Price', 'qode-tours' ),
				'parent'        => $info_section_container
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_duration',
				'type'          => 'text',
				'default_value' => '',
				'label'         => esc_html__( 'Duration', 'qode-tours' ),
				'parent'        => $info_section_container
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_destination',
				'type'          => 'select',
				'default_value' => '',
				'label'         => esc_html__( 'Destination', 'qode-tours' ),
				'options'       => $destinations,
				'parent'        => $info_section_container
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_custom_label',
				'type'          => 'text',
				'default_value' => '',
				'label'         => esc_html__( 'Custom Label', 'qode-tours' ),
				'description'   => esc_html__( 'Define custom label which will show on tour lists and tour single pages', 'qode-tours' ),
				'parent'        => $info_section_container
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_info_min_years',
				'type'          => 'text',
				'default_value' => '',
				'label'         => esc_html__( 'Minimum Years Required', 'qode-tours' ),
				'parent'        => $info_section_container
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_departure',
				'type'          => 'text',
				'default_value' => '',
				'label'         => esc_html__( 'Departure/Return Location', 'qode-tours' ),
				'parent'        => $info_section_container
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_departure_time',
				'type'          => 'text',
				'default_value' => '',
				'label'         => esc_html__( 'Departure Time', 'qode-tours' ),
				'parent'        => $info_section_container
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_return_time',
				'type'          => 'text',
				'default_value' => '',
				'label'         => esc_html__( 'Return Time', 'qode-tours' ),
				'parent'        => $info_section_container
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_dress_code',
				'type'          => 'text',
				'default_value' => '',
				'label'         => esc_html__( 'Dress Code', 'qode-tours' ),
				'parent'        => $info_section_container
			)
		);
		
		$masonry_section_container = bridge_qode_add_admin_container(
			array(
				'type'   => 'container',
				'name'   => 'masonry_section_container',
				'parent' => $info_section_meta_box
			)
		);
		
		bridge_qode_add_admin_section_title(
			array(
				'name'   => 'masonry_section_title',
				'parent' => $masonry_section_container,
				'title'  => esc_html__( 'Masonry List Settings', 'qode-tours' )
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_masonry_layout',
				'type'          => 'select',
				'default_value' => '',
				'label'         => esc_html__( 'Masonry Layout', 'qode-tours' ),
				'options'       => array(
					'default'     => esc_html__( 'Default', 'qode-tours' ),
					'description' => esc_html__( 'Description', 'qode-tours' )
				),
				'parent'        => $masonry_section_container
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_masonry_dimensions',
				'type'          => 'select',
				'default_value' => '',
				'label'         => esc_html__( 'Masonry Dimensions', 'qode-tours' ),
				'options'       => array(
					'default'            => esc_html__( 'Default', 'qode-tours' ),
					'large-width'        => esc_html__( 'Large Width', 'qode-tours' ),
					'large-height'       => esc_html__( 'Large Height', 'qode-tours' ),
					'large-width-height' => esc_html__( 'Large Width/Height', 'qode-tours' )
				),
				'parent'        => $masonry_section_container
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_list_image',
				'type'          => 'image',
				'default_value' => '',
				'label'         => esc_html__( 'List Image', 'qode-tours' ),
				'parent'        => $masonry_section_container
			)
		);
	}
	
	add_action( 'bridge_qode_action_meta_boxes_map', 'qode_tours_info_section_map' );
}

if ( ! function_exists( 'qode_tours_tour_plan_section_map' ) ) {
	function qode_tours_tour_plan_section_map() {
		
		$tour_plan_section_meta_box = bridge_qode_create_meta_box(
			array(
				'scope' => array( 'tour-item' ),
				'title' => esc_html__( 'Tour Plan', 'qode-tours' ),
				'name'  => 'tours_plan_section_meta'
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_show_tour_plan_section',
				'type'          => 'yesno',
				'default_value' => 'yes',
				'label'         => esc_html__( 'Show Tour Plan Section', 'qode-tours' ),
				'parent'        => $tour_plan_section_meta_box
			)
		);
		
		$tour_plan_section_container = bridge_qode_add_admin_container_no_style(
			array(
				'type'       => 'container',
				'name'       => 'tour_plan_section_container',
				'parent'     => $tour_plan_section_meta_box,
				'dependency' => array(
					'show' => array(
						'qode_tours_show_tour_plan_section' => 'yes'
					)
				)
			)
		);
		
		bridge_qode_add_repeater_field(
			array(
				'name'        => 'qode_tour_plan_repeater',
				'parent'      => $tour_plan_section_container,
				'button_text' => esc_html__( 'Add new Tour Plan Section', 'qode-tours' ),
				'fields'      => array(
					array(
						'type'        => 'text',
						'name'        => 'plan_section_title',
						'label'       => esc_html__( 'Tour Plan Section Title', 'qode-tours' ),
						'description' => esc_html__( 'Description', 'qode-tours' )
					),
					array(
						'type'        => 'textareahtml',
						'name'        => 'plan_section_description',
						'label'       => esc_html__( 'Tour Plan Section Description', 'qode-tours' ),
						'description' => esc_html__( 'Description field', 'qode-tours' )
					)
				)
			)
		);
	}
	
	add_action( 'bridge_qode_action_meta_boxes_map', 'qode_tours_tour_plan_section_map' );
}

if ( ! function_exists( 'qode_tours_location_section_map' ) ) {
	function qode_tours_location_section_map() {
		
		$location_section_meta_box = bridge_qode_create_meta_box(
			array(
				'scope' => array( 'tour-item' ),
				'title' => esc_html__( 'Location Section', 'qode-tours' ),
				'name'  => 'location_section_meta'
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_show_location_section',
				'type'          => 'yesno',
				'default_value' => 'yes',
				'label'         => esc_html__( 'Show Location Section', 'qode-tours' ),
				'parent'        => $location_section_meta_box
			)
		);
		
		$location_section_container = bridge_qode_add_admin_container_no_style(
			array(
				'type'       => 'container',
				'name'       => 'location_section_container',
				'parent'     => $location_section_meta_box,
				'dependency' => array(
					'show' => array(
						'qode_tours_show_location_section' => 'yes'
					)
				)
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_location_excerpt',
				'type'          => 'text',
				'default_value' => '',
				'label'         => esc_html__( 'Location Excerpt', 'qode-tours' ),
				'parent'        => $location_section_container
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_location_address1',
				'type'          => 'text',
				'default_value' => '',
				'label'         => esc_html__( 'Address 1', 'qode-tours' ),
				'parent'        => $location_section_container
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_location_address2',
				'type'          => 'text',
				'default_value' => '',
				'label'         => esc_html__( 'Address 2', 'qode-tours' ),
				'parent'        => $location_section_container
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_location_address3',
				'type'          => 'text',
				'default_value' => '',
				'label'         => esc_html__( 'Address 3', 'qode-tours' ),
				'parent'        => $location_section_container
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_location_address4',
				'type'          => 'text',
				'default_value' => '',
				'label'         => esc_html__( 'Address 4', 'qode-tours' ),
				'parent'        => $location_section_container
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_location_address5',
				'type'          => 'text',
				'default_value' => '',
				'label'         => esc_html__( 'Address 5', 'qode-tours' ),
				'parent'        => $location_section_container
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_location_content',
				'type'          => 'textareahtml',
				'default_value' => '',
				'label'         => esc_html__( 'Location Content', 'qode-tours' ),
				'parent'        => $location_section_container
			)
		);
	}
	
	add_action( 'bridge_qode_action_meta_boxes_map', 'qode_tours_location_section_map' );
}

if ( ! function_exists( 'qode_tours_gallery_section_map' ) ) {
	function qode_tours_gallery_section_map() {
		
		$gallery_section_meta_box = bridge_qode_create_meta_box(
			array(
				'scope' => array( 'tour-item' ),
				'title' => esc_html__( 'Gallery Section', 'qode-tours' ),
				'name'  => 'gallery_section_meta'
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_show_gallery_section',
				'type'          => 'yesno',
				'default_value' => 'yes',
				'label'         => esc_html__( 'Show Gallery Section', 'qode-tours' ),
				'parent'        => $gallery_section_meta_box
			)
		);
		
		$gallery_section_container = bridge_qode_add_admin_container_no_style(
			array(
				'type'       => 'container',
				'name'       => 'gallery_section_container',
				'parent'     => $gallery_section_meta_box,
				'dependency' => array(
					'show' => array(
						'qode_tours_show_gallery_section' => 'yes'
					)
				)
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_gallery_excerpt',
				'type'          => 'text',
				'default_value' => '',
				'label'         => esc_html__( 'Excerpt', 'qode-tours' ),
				'parent'        => $gallery_section_container
			)
		);
		
		bridge_qode_add_multiple_images_field(
			array(
				'parent'      => $gallery_section_container,
				'name'        => 'qode_tours_gallery_images',
				'label'       => esc_html__( 'Gallery Images', 'qode-tours' ),
				'description' => esc_html__( 'Choose your gallery images', 'qode-tours' )
			)
		);
	}
	
	add_action( 'bridge_qode_action_meta_boxes_map', 'qode_tours_gallery_section_map' );
}

if ( ! function_exists( 'qode_tours_review_section_map' ) ) {
	function qode_tours_review_section_map() {
		
		$review_section_meta_box = bridge_qode_create_meta_box(
			array(
				'scope' => array( 'tour-item' ),
				'title' => esc_html__( 'Review Section', 'qode-tours' ),
				'name'  => 'review_section_meta'
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_show_review_section',
				'type'          => 'yesno',
				'label'         => esc_html__( 'Show Review Section', 'qode-tours' ),
				'parent'        => $review_section_meta_box,
				'default_value' => 'yes'
			)
		);
	}
	
	add_action( 'bridge_qode_action_meta_boxes_map', 'qode_tours_review_section_map' );
}

if ( ! function_exists( 'qode_tours_custom_section_1_map' ) ) {
	function qode_tours_custom_section_1_map() {
		
		$custom_section_1_meta_box = bridge_qode_create_meta_box(
			array(
				'scope' => array( 'tour-item' ),
				'title' => esc_html__( 'Custom Section 1', 'qode-tours' ),
				'name'  => 'custom_section_1_meta'
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_show_custom_section_1',
				'type'          => 'yesno',
				'default_value' => 'no',
				'label'         => esc_html__( 'Show Custom Section 1', 'qode-tours' ),
				'parent'        => $custom_section_1_meta_box
			)
		);
		
		$custom_section_1_container = bridge_qode_add_admin_container_no_style(
			array(
				'type'       => 'container',
				'name'       => 'custom_section_1_container',
				'parent'     => $custom_section_1_meta_box,
				'dependency' => array(
					'show' => array(
						'qode_tours_show_custom_section_1' => 'yes'
					)
				)
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_custom_section1_title',
				'type'          => 'text',
				'default_value' => '',
				'label'         => esc_html__( 'Title', 'qode-tours' ),
				'parent'        => $custom_section_1_container
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_custom_section1_content',
				'type'          => 'textareahtml',
				'default_value' => '',
				'label'         => esc_html__( 'Content', 'qode-tours' ),
				'parent'        => $custom_section_1_container
			)
		);
	}
	
	add_action( 'bridge_qode_action_meta_boxes_map', 'qode_tours_custom_section_1_map' );
}

if ( ! function_exists( 'qode_tours_custom_section_2_map' ) ) {
	function qode_tours_custom_section_2_map() {
		
		$custom_section_2_meta_box = bridge_qode_create_meta_box(
			array(
				'scope' => array( 'tour-item' ),
				'title' => esc_html__( 'Custom Section 2', 'qode-tours' ),
				'name'  => 'custom_section_2_meta'
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_show_custom_section_2',
				'type'          => 'yesno',
				'default_value' => 'no',
				'label'         => esc_html__( 'Show Custom Section 2', 'qode-tours' ),
				'parent'        => $custom_section_2_meta_box
			)
		);
		
		$custom_section_2_container = bridge_qode_add_admin_container_no_style(
			array(
				'type'       => 'container',
				'name'       => 'custom_section_2_container',
				'parent'     => $custom_section_2_meta_box,
				'dependency' => array(
					'show' => array(
						'qode_tours_show_custom_section_2' => 'yes'
					)
				)
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_custom_section2_title',
				'type'          => 'text',
				'default_value' => '',
				'label'         => esc_html__( 'Title', 'qode-tours' ),
				'parent'        => $custom_section_2_container
			)
		);
		
		bridge_qode_create_meta_box_field(
			array(
				'name'          => 'qode_tours_custom_section2_content',
				'type'          => 'textareahtml',
				'default_value' => '',
				'label'         => esc_html__( 'Content', 'qode-tours' ),
				'parent'        => $custom_section_2_container
			)
		);
	}
	
	add_action( 'bridge_qode_action_meta_boxes_map', 'qode_tours_custom_section_2_map' );
}