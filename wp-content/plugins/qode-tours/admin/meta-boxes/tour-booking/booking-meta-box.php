<?php

if ( ! function_exists( 'qode_tours_map_booking_meta' ) ) {
	function qode_tours_map_booking_meta() {
		global $post;

		$tour_booking_meta_box = bridge_qode_create_meta_box(
			array(
				'scope' => apply_filters( 'qode_set_scope_for_meta_boxes', array( 'tour-item' ), 'tour_booking_meta' ),
				'title' => esc_html__( 'Tour Booking', 'qode-tours' ),
				'name'  => 'tour_booking_meta'
			)
		);
		
		bridge_qode_add_repeater_field( array(
				'name'        => 'tour_booking',
				'parent'      => $tour_booking_meta_box,
				'button_text' => esc_html__( 'Add New Period', 'qode-tours' ),
				'fields'      => array(
					array(
						'name'      => 'start_date',
						'type'      => 'date',
						'label'     => esc_html__( 'Start Date', 'qode-tours' ),
						'col_width' => '6',
						'args'      => array(
							'col_width' => '12',
                            'formatted_date' => 'yes'
						)
					),
					array(
						'name'      => 'end_date',
						'type'      => 'date',
						'label'     => esc_html__( 'End Date', 'qode-tours' ),
						'col_width' => '6',
						'args'      => array(
							'col_width' => '12',
                            'formatted_date' => 'yes'
						)
					),
					array(
						'name'      => 'days',
						'type'      => 'checkboxgroup',
						'label'     => esc_html__( 'Tour Days', 'qode-tours' ),
						'options'   => array(
							'Mon' => esc_html__( 'Monday', 'qode-tours' ),
							'Tue' => esc_html__( 'Tuesday', 'qode-tours' ),
							'Wed' => esc_html__( 'Wednesday', 'qode-tours' ),
							'Thu' => esc_html__( 'Thursday', 'qode-tours' ),
							'Fri' => esc_html__( 'Friday', 'qode-tours' ),
							'Sat' => esc_html__( 'Saturday', 'qode-tours' ),
							'Sun' => esc_html__( 'Sunday', 'qode-tours' )
						),
						'col_width' => '12'
					),
					array(
						'name'        => 'tour_time',
						'type'        => 'repeater',
						'label'       => esc_html__( 'Departure Time', 'qode-tours' ),
						'button_text' => esc_html__( 'Add New Time', 'qode-tours' ),
						'fields'      => array(
							array(
								'name' => 'time',
								'type' => 'text',
								'args' => array(
									'col_width' => '3'
								)
							)
						)
					),
					array(
						'name'      => 'number_of_tickets',
						'type'      => 'text',
						'label'     => esc_html__( 'Tickets', 'qode-tours' ),
						'col_width' => '3'
					),
					array(
						'name'        => 'price_change',
						'type'        => 'text',
						'label'       => esc_html__( 'Price Changes', 'qode-tours' ),
						'description' => esc_html__( 'Use this field for defining special price for this period. Use "%" in front of the number to change the price in percentage.', 'qode-tours' ),
						'col_width'   => '9'
					),
				)
			)
		);
	}
	
	add_action( 'bridge_qode_action_meta_boxes_map', 'qode_tours_map_booking_meta', 10 );
}