<?php

namespace QodeTours\CPT\Tours\Lib;

use QodeTours\Admin\MetaBoxes\TourBooking\TourTimeStorage;

class ToursQuery {
	/**
	 * @var private instance of current class
	 */
	private static $instance;

	/**
	 * Private constuct because of Singletone
	 */
	private function __construct() {
	}

	/**
	 * Private sleep because of Singletone
	 */
	public function __wakeup() {
	}

	/**
	 * Private clone because of Singletone
	 */
	private function __clone() {
	}

	/**
	 * Returns current instance of class
	 * @return ToursQuery
	 */
	public static function getInstance() {
		if(self::$instance == null) {
			return new self;
		}

		return self::$instance;
	}

	public function queryVCParams() {
		return array(
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__('Number of Tours Per Page', 'qode-tours'),
				'param_name'  => 'number',
				'value'       => '-1',
				'description' => esc_html__('Enter -1 to show all', 'qode-tours'),
				'group'       => esc_html__('Query Options', 'qode-tours')
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__('Order By', 'qode-tours'),
				'param_name'  => 'order_by',
				'value'       => array(
					esc_html__('Title', 'qode-tours')      => 'title',
					esc_html__('Date', 'qode-tours')       => 'date',
					esc_html__('Menu Order', 'qode-tours') => 'menu_order',
				),
				'save_always' => true,
				'group'       => esc_html__('Query Options', 'qode-tours')
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__('Order', 'qode-tours'),
				'param_name'  => 'order',
				'value'       => array(
					esc_html__('ASC', 'qode-tours')  => 'ASC',
					esc_html__('DESC', 'qode-tours') => 'DESC',
				),
				'save_always' => true,
				'group'       => esc_html__('Query Options', 'qode-tours')
			),
			array(
				'type'        => 'textfield',
				'param_name'  => 'tour_category',
				'heading'     => esc_html__('Tour Category', 'qode-tours'),
				'description' => esc_html__('Enter one tour category slug (leave empty for showing all categories)', 'qode-tours'),
				'group'       => esc_html__('Query Options', 'qode-tours')
			),
			array(
				'type'        => 'textfield',
				'param_name'  => 'selected_tours',
				'heading'     => esc_html__('Show Only Tours with Listed IDs', 'qode-tours'),
				'description' => esc_html__('Delimit ID numbers by comma (leave empty for all)', 'qode-tours'),
				'group'       => esc_html__('Query Options', 'qode-tours')
			),
			array(
				'type'        => 'textfield',
				'param_name'  => 'destination',
				'heading'     => esc_html__('Destination Name', 'qode-tours'),
				'description' => esc_html__('Display tour items from filled destination', 'qode-tours'),
				'group'       => esc_html__('Query Options', 'qode-tours')
			)
		);
	}

	public function getShortcodeAtts() {
		return array(
			'number'         => '-1',
			'order_by'       => 'date',
			'order'          => 'ASC',
			'tour_category'  => '',
			'selected_tours' => '',
			'destination'    => '',
			'paged'          => ''
		);
	}

	public function buildQueryObject($params, $meta_query_array = null) {
		$queryArray = array(
			'post_status'    => 'publish',
			'post_type'      => 'tour-item',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'posts_per_page' => '-1'
		);

		if(!empty($params['order_by'])) {
			$queryArray['orderby'] = $params['order_by'];
		}

		if(!empty($params['order'])) {
			$queryArray['order'] = $params['order'];
		}

		if(!empty($params['number'])) {
			$queryArray['posts_per_page'] = $params['number'];
		}

		if(!empty($params['tour_category'])) {
			$queryArray['tour-category'] = $params['tour_category'];
		}

		$toursIds = null;
		if(!empty($params['selected_tours'])) {
			$toursIds             = explode(',', $params['selected_tours']);
			$queryArray['post__in'] = $toursIds;
		}

		if(!empty($params['next_page'])) {
			$queryArray['paged'] = $params['next_page'];

		} else {
			$queryArray['paged'] = 1;
		}

		if(is_array($meta_query_array) && count($meta_query_array)) {
			$queryArray = array_merge($queryArray, $meta_query_array);
		}

		return new \WP_Query($queryArray);
	}
}
