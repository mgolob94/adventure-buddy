<?php

if ( ! function_exists( 'qode_tours_tour_options_map' ) ) {
	function qode_tours_tour_options_map() {
		
		bridge_qode_add_admin_page(
			array(
				'slug'  => '_tours_page',
				'title' => esc_html__( 'Tours', 'qode-tours' ),
				'icon'  => 'fa fa-camera-retro'
			)
		);
		
		$panel_payment = bridge_qode_add_admin_panel(
			array(
				'title' => esc_html__( 'Payment', 'qode-tours' ),
				'name'  => 'panel_payment',
				'page'  => '_tours_page'
			)
		);
		
		bridge_qode_add_admin_section_title(
			array(
				'parent' => $panel_payment,
				'name'   => 'paypal_section_title',
				'title'  => esc_html__( 'PayPal', 'qode-tours' )
			)
		);
		
		bridge_qode_add_admin_field(
			array(
				'name'          => 'tours_enable_paypal',
				'type'          => 'yesno',
				'default_value' => 'yes',
				'label'         => esc_html__( 'Enable Paypal', 'qode-tours' ),
				'description'   => esc_html__( 'This option will enable/disable Paypal payment', 'qode-tours' ),
				'parent'        => $panel_payment,
				'args'          => array(
					"dependence"             => true,
					"dependence_hide_on_yes" => "",
					"dependence_show_on_yes" => "#qodef_show_paypal_container"
				)
			)
		);
		
		$show_paypal_container = bridge_qode_add_admin_container(
			array(
				'parent'     => $panel_payment,
				'name'       => 'show_paypal_container',
				'hidden_property' => 'tours_enable_paypal',
                'hidden_value'    => 'no'
			)
		);
		
		bridge_qode_add_admin_field(
			array(
				'name'          => 'paypal_facilitator_id',
				'type'          => 'text',
				'default_value' => '',
				'label'         => esc_html__( 'Account ID', 'qode-tours' ),
				'description'   => esc_html__( 'Insert Business Account ID (Email)', 'qode-tours' ),
				'parent'        => $show_paypal_container
			)
		);
		
		bridge_qode_add_admin_field(
			array(
				'name'          => 'paypal_currency',
				'type'          => 'select',
				'default_value' => 'USD',
				'label'         => esc_html__( 'Currency', 'qode-tours' ),
				'description'   => esc_html__( 'Payment Currency', 'qode-tours' ),
				'parent'        => $show_paypal_container,
				'options'       => array(
					'USD' => esc_html__( 'U.S. Dollar', 'qode-tours' ),
					'EUR' => esc_html__( 'Euro', 'qode-tours' ),
					'GBP' => esc_html__( 'Pound Sterling', 'qode-tours' ),
					'AUD' => esc_html__( 'Australian Dollar', 'qode-tours' ),
					'CHF' => esc_html__( 'Swiss Franc', 'qode-tours' ),
					'BRL' => esc_html__( 'Brazilian Real', 'qode-tours' ),
					'CAD' => esc_html__( 'Canadian Dollar', 'qode-tours' ),
					'CZK' => esc_html__( 'Czech Koruna', 'qode-tours' ),
					'DKK' => esc_html__( 'Danish Krone', 'qode-tours' ),
					'HKD' => esc_html__( 'Hong Kong Dollar', 'qode-tours' ),
					'HUF' => esc_html__( 'Hungarian Forint', 'qode-tours' ),
					'ILS' => esc_html__( 'Israeli New Sheqel', 'qode-tours' ),
					'JPY' => esc_html__( 'Japanese Yen', 'qode-tours' ),
					'MYR' => esc_html__( 'Malaysian Ringgit', 'qode-tours' ),
					'MXN' => esc_html__( 'Mexican Peso', 'qode-tours' ),
					'NOK' => esc_html__( 'Norwegian Krone', 'qode-tours' ),
					'NZD' => esc_html__( 'New Zealand Dollar', 'qode-tours' ),
					'PHP' => esc_html__( 'Philippine Peso', 'qode-tours' ),
					'PLN' => esc_html__( 'Polish Zloty', 'qode-tours' ),
					'SGD' => esc_html__( 'Singapore Dollar', 'qode-tours' ),
					'SEK' => esc_html__( 'Swedish Krona', 'qode-tours' ),
					'TWD' => esc_html__( 'Taiwan New Dollar', 'qode-tours' ),
					'THB' => esc_html__( 'Thai Baht', 'qode-tours' ),
					'TRY' => esc_html__( 'Turkish Lira', 'qode-tours' )
				)
			)
		);
		
		$settings_panel = bridge_qode_add_admin_panel(
			array(
				'title' => esc_html__( 'Settings', 'qode-tours' ),
				'name'  => 'panel_settings',
				'page'  => '_tours_page'
			)
		);
		
		$checkout_pages = qode_tours_get_checkout_pages( true );
		
		bridge_qode_add_admin_field(
			array(
				'name'          => 'tours_checkout_page',
				'type'          => 'select',
				'default_value' => '',
				'label'         => esc_html__( 'Checkout Page', 'qode-tours' ),
				'description'   => esc_html__( 'Choose checkout page', 'qode-tours' ),
				'parent'        => $settings_panel,
				'options'       => $checkout_pages,
				'args'          => array(
					'col_width' => 3
				)
			)
		);
		
		bridge_qode_add_admin_field(
			array(
				'name'          => 'tours_currency_symbol',
				'type'          => 'text',
				'default_value' => '',
				'label'         => esc_html__( 'Price Currency', 'qode-tours' ),
				'description'   => esc_html__( 'Insert currency for tour prices', 'qode-tours' ),
				'parent'        => $settings_panel,
				'args'          => array(
					'col_width' => '3'
				)
			)
		);
		
		bridge_qode_add_admin_field(
			array(
				'name'          => 'tours_currency_symbol_position',
				'type'          => 'select',
				'default_value' => 'left',
				'label'         => esc_html__( 'Price Currency Position', 'qode-tours' ),
				'description'   => esc_html__( 'Choose position for your currency symbol', 'qode-tours' ),
				'parent'        => $settings_panel,
				'options'       => array(
					'left'  => esc_html__( 'Left', 'qode-tours' ),
					'right' => esc_html__( 'Right', 'qode-tours' )
				),
				'args'          => array(
					'col_width' => 3
				)
			)
		);
		
		$search_pages = qode_tours_get_search_pages( true );
		
		$search_panel = bridge_qode_add_admin_panel(
			array(
				'title' => esc_html__( 'Search Page', 'qode-tours' ),
				'name'  => 'panel_search',
				'page'  => '_tours_page'
			)
		);
		
		bridge_qode_add_admin_field(
			array(
				'parent'        => $search_panel,
				'type'          => 'select',
				'name'          => 'tours_search_main_page',
				'default_value' => '',
				'label'         => esc_html__( 'Main Search Page', 'qode-tours' ),
				'description'   => esc_html__( 'Choose main search page. Defaults to tour item archive page', 'qode-tours' ),
				'options'       => $search_pages,
				'args'          => array(
					'col_width' => 3
				)
			)
		);
		
		bridge_qode_add_admin_field(
			array(
				'parent'        => $search_panel,
				'type'          => 'text',
				'name'          => 'tours_per_page',
				'default_value' => 12,
				'label'         => esc_html__( 'Items per Page', 'qode-tours' ),
				'description'   => esc_html__( 'Choose number of tour items per page', 'qode-tours' ),
				'args'          => array(
					'col_width' => 3
				)
			)
		);
		
		bridge_qode_add_admin_field(
			array(
				'parent'        => $search_panel,
				'type'          => 'select',
				'name'          => 'tours_search_default_view_type',
				'default_value' => 'list',
				'label'         => esc_html__( 'Default Tour View Type', 'qode-tours' ),
				'description'   => esc_html__( 'Choose default tour view type', 'qode-tours' ),
				'options'       => array(
					'list'     => esc_html__( 'List', 'qode-tours' ),
					'standard' => esc_html__( 'Standard', 'qode-tours' ),
					'gallery'  => esc_html__( 'Gallery', 'qode-tours' )
				),
				'args'          => array(
					'col_width' => 3,
					'dependence' => true,
                    'hide'       => array(
                        'list'    	=> '#qodef_standard_container, #qodef_gallery_container',
                        'standard'  => '#qodef_list_container, #qodef_gallery_container',
                        'gallery'   => '#qodef_list_container, #qodef_standard_container'
                    ),
                    'show'       => array(
                        'list'   	=> '#qodef_list_container',
                        'standard'	=> '#qodef_standard_container',
                        'gallery'	=> '#qodef_gallery_container',
                    )
				)
			)
		);
		
		bridge_qode_add_admin_field(
			array(
				'parent'        => $search_panel,
				'type'          => 'select',
				'name'          => 'tours_search_default_ordering',
				'default_value' => 'date',
				'label'         => esc_html__( 'Default Tour Ordering', 'qode-tours' ),
				'description'   => esc_html__( 'Choose default tour ordering', 'qode-tours' ),
				'options'       => array(
					'date'       => esc_html__( 'Date', 'qode-tours' ),
					'price_low'  => esc_html__( 'Price Low to High', 'qode-tours' ),
					'price_high' => esc_html__( 'Price High to Low', 'qode-tours' ),
					'name'       => esc_html__( 'Name', 'qode-tours' )
				),
				'args'          => array(
					'col_width' => 3
				)
			)
		);

		$standard_container = bridge_qode_add_admin_container(
			array(
				'parent'     => $search_panel,
				'name'       => 'standard_container',
                'hidden_property' => 'tours_search_default_view_type',
                'hidden_values'    => array(
                	'list',
                	'gallery'
                )
			)
		);
		
		bridge_qode_add_admin_field(
			array(
				'parent'        => $standard_container,
				'type'          => 'text',
				'name'          => 'tours_standard_text_length',
				'default_value' => 55,
				'label'         => esc_html__( 'Standard Item Text Length', 'qode-tours' ),
				'description'   => esc_html__( 'Choose number of words for standard tour item', 'qode-tours' ),
				'args'          => array(
					'col_width' => 3
				)
			)
		);
		
		bridge_qode_add_admin_field(
			array(
				'parent'        => $standard_container,
				'type'          => 'select',
				'name'          => 'tours_standard_thumb_size',
				'default_value' => 'full',
				'label'         => esc_html__( 'Standard Thumbnail Size', 'qode-tours' ),
				'description'   => esc_html__( 'Choose thumbnail size for standard tour item', 'qode-tours' ),
				'options'       => array(
					'full'                           => esc_html__( 'Full', 'qode-tours' ),
					'portfolio-landscape' => esc_html__( 'Landscape', 'qode-tours' ),
					'portfolio-portrait'  => esc_html__( 'Portrait', 'qode-tours' ),
					'portfolio-square'    => esc_html__( 'Square', 'qode-tours' )
				),
				'args'          => array(
					'col_width' => 3
				)
			)
		);

		$gallery_container = bridge_qode_add_admin_container(
			array(
				'parent'     => $search_panel,
				'name'       => 'gallery_container',
                'hidden_property' => 'tours_search_default_view_type',
                'hidden_values'    => array(
                	'list',
                	'standard'
                )
			)
		);
		
		bridge_qode_add_admin_field(
			array(
				'parent'        => $gallery_container,
				'type'          => 'text',
				'name'          => 'tours_gallery_text_length',
				'default_value' => 55,
				'label'         => esc_html__( 'Gallery Item Text Length', 'qode-tours' ),
				'description'   => esc_html__( 'Choose number of words for gallery tour item', 'qode-tours' ),
				'args'          => array(
					'col_width' => 3
				)
			)
		);
		
		bridge_qode_add_admin_field(
			array(
				'parent'        => $gallery_container,
				'type'          => 'select',
				'name'          => 'tours_gallery_thumb_size',
				'default_value' => 'full',
				'options'       => array(
					'full'                           => esc_html__( 'Full', 'qode-tours' ),
					'portfolio-landscape' => esc_html__( 'Landscape', 'qode-tours' ),
					'portfolio-portrait'  => esc_html__( 'Portrait', 'qode-tours' ),
					'portfolio-square'    => esc_html__( 'Square', 'qode-tours' )
				),
				'label'         => esc_html__( 'Gallery Thumbnail Size', 'qode-tours' ),
				'description'   => esc_html__( 'Choose thumbnail size for gallery tour item', 'qode-tours' ),
				'args'          => array(
					'col_width' => 3
				)
			)
		);

		$list_container = bridge_qode_add_admin_container(
			array(
				'parent'     => $search_panel,
				'name'       => 'list_container',
                'hidden_property' => 'tours_search_default_view_type',
                'hidden_values'    => array(
                	'standard',
                	'gallery'
                )
			)
		);
		
		bridge_qode_add_admin_field(
			array(
				'parent'        => $list_container,
				'type'          => 'text',
				'name'          => 'tours_list_text_length',
				'default_value' => 55,
				'label'         => esc_html__( 'List Item Text Length', 'qode-tours' ),
				'description'   => esc_html__( 'Choose number of words for list tour item', 'qode-tours' ),
				'args'          => array(
					'col_width' => 3
				)
			)
		);
		
		$reviews_panel = bridge_qode_add_admin_panel(
			array(
				'title' => esc_html__( 'Reviews', 'qode-tours' ),
				'name'  => 'panel_reviews',
				'page'  => '_tours_page'
			)
		);
		
		bridge_qode_add_admin_field(
			array(
				'parent'        => $reviews_panel,
				'type'          => 'text',
				'name'          => 'reviews_section_title',
				'default_value' => '',
				'label'         => esc_html__( 'Reviews Section Title', 'qode-tours' ),
				'description'   => esc_html__( 'Enter title that you want to show before average rating for each tour', 'qode-tours' ),
			)
		);
		
		bridge_qode_add_admin_field(
			array(
				'parent'        => $reviews_panel,
				'type'          => 'textarea',
				'name'          => 'reviews_section_subtitle',
				'default_value' => '',
				'label'         => esc_html__( 'Reviews Section Subtitle', 'qode-tours' ),
				'description'   => esc_html__( 'Enter subtitle that you want to show before average rating for each tour', 'qode-tours' ),
			)
		);

        bridge_qode_add_admin_field(
            array(
                'parent'        => $reviews_panel,
                'type'          => 'yesno',
                'name'          => 'display_reviews_comments',
                'default_value' => 'yes',
                'label'         => esc_html__( 'Display review\'s comments in reviews section', 'qode-tours' ),
                'description'   => esc_html__( 'Enable this option if you would like to show actual reviews on reviews section', 'qode-tours' )
            )
        );

        bridge_qode_add_admin_field(
            array(
                'parent'        => $reviews_panel,
                'type'          => 'select',
                'name'          => 'review_section_template',
                'default_value' => 'per-criteria',
                'label'         => esc_html__( 'Reviews Section Template', 'qode-tours' ),
                'description'   => esc_html__( 'Please choose template for displaying reviews section on single tour item', 'qode-tours' ),
                'options'       => array(
                    'per-criteria'  => esc_html__( 'Per Criteria', 'qode-tours' ),
                    'per-mark' => esc_html__( 'Per Mark', 'qode-tours' ),
                    'simple'  => esc_html__( 'Simple', 'qode-tours' ),
                    'stars' => esc_html__( 'Stars', 'qode-tours' ),
                    'default'   => esc_html__('Default Comments List', 'qode_tours')
                )
            )
        );

		$panel_tour_sidebar = bridge_qode_add_admin_panel(
			array(
				'title' => esc_html__( 'Tour Item Sidebar', 'qode-tours' ),
				'name'  => 'panel_tour_sidebar',
				'page'  => '_tours_page'
			)
		);

		bridge_qode_add_admin_field(
			array(
				'parent'        => $panel_tour_sidebar,
				'type'          => 'image',
				'name'          => 'booking_form_bg_image',
				'label'         => esc_html__( 'Tour Item Booking Form Background Image', 'qode-tours' ),
				'description'   => esc_html__( 'Choose background image for booking form present on single tour item', 'qode-tours' )
			)
		);


		
		$panel_admin_email = bridge_qode_add_admin_panel(
			array(
				'title' => esc_html__( 'Admin Booking Email', 'qode-tours' ),
				'name'  => 'admin_email',
				'page'  => '_tours_page'
			)
		);
		
		bridge_qode_add_admin_field(
			array(
				'parent'        => $panel_admin_email,
				'type'          => 'yesno',
				'name'          => 'enable_admin_booking_email',
				'default_value' => 'yes',
				'label'         => esc_html__( 'Should Admin Receive Booking Emails?', 'qode-tours' ),
				'description'   => esc_html__( 'Enabling this option will forward all booking emails to the site administrator’s inbox', 'qode-tours' ),
				'args'          => array(
					"dependence"             => true,
					"dependence_hide_on_yes" => "",
					"dependence_show_on_yes" => "#qodef_show_admin_email_container"
				)
			)
		);
		
		$show_admin_email_container = bridge_qode_add_admin_container(
			array(
				'parent'     => $panel_admin_email,
				'name'       => 'show_admin_email_container',
				'hidden_property' => 'enable_admin_booking_email',
                'hidden_value'    => 'no'
			)
		);
		
		bridge_qode_add_admin_field(
			array(
				'name'          => 'admin_email',
				'type'          => 'text',
				'default_value' => '',
				'label'         => esc_html__( 'Admin Email', 'qode-tours' ),
				'description'   => esc_html__( 'Input the site administrator’s email address. If you leave this field empty, booking emails will be sent to the default admin’s email address', 'qode-tours' ),
				'parent'        => $show_admin_email_container
			)
		);
	}
	
	add_action( 'bridge_qode_action_options_map', 'qode_tours_tour_options_map', 11 );
}