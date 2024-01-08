<?php
namespace QodeTours\CPT\Destination\Shortcodes;

use QodeTours\Lib\ShortcodeInterface;

class DestinationGrid implements ShortcodeInterface {
	private $base;

	/**
	 * DestinationGrid constructor.
	 */
	public function __construct() {
		$this->base = 'qode_destination_grid';

		add_action('vc_before_init', array($this, 'vcMap'));
	}


	public function getBase() {
		return $this->base;
	}

	public function vcMap() {
		vc_map(array(
			'name'                      => esc_html__('Destinations Grid', 'qode-tours'),
			'base'                      => $this->base,
			'category'                  => esc_html__('by QODE TOURS', 'qode-tours'),
			'icon'                      => 'icon-wpb-destinations-grid extended-custom-icon-qode',
			'allowed_container_element' => 'vc_row',
			'params'                    => array(
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__('Number of Columns', 'qode-tours'),
					'param_name'  => 'number_of_cols',
					'value'       => array(
						esc_html__('Default','qode-tours') => '3',
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5'	=> '5',
						'6'	=> '6'
					)
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__('Image Proportions', 'qode-tours'),
					'param_name'  => 'image_size',
					'value'       => array(
						esc_html__('Default', 'qode-tours')   => '',
						esc_html__('Original', 'qode-tours')  => 'full',
						esc_html__('Square', 'qode-tours')    => 'square',
						esc_html__('Landscape', 'qode-tours') => 'landscape',
						esc_html__('Portrait', 'qode-tours')  => 'portrait',
						esc_html__('Custom', 'qode-tours')    => 'custom'
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__('Image Dimensions', 'qode-tours'),
					'param_name'  => 'custom_image_dimensions',
					'description' => esc_html__('Enter custom image dimensions. Enter image size in pixels: 200x100 (Width x Height)', 'qode-tours'),
					'dependency'  => array('element' => 'image_size', 'value' => 'custom')
				),
				array(
		            'type' => 'dropdown',
		            'heading' => esc_html__('Space Between Items','qode-tours'),
		            'param_name' => 'space_between_items',
		            'value' => array(
		                esc_html__('Medium', 'qode-tours') => 'normal',
		                esc_html__('Small', 'qode-tours') => 'small',
		                esc_html__('Tiny', 'qode-tours') => 'tiny',
		                esc_html__('None', 'qode-tours') => 'no',
		            )
	            ),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'title_tag',
					'heading'     => esc_html__( 'Title Tag', 'qode-tours' ),
					'value'       => array_flip( bridge_qode_get_title_tag( true ) ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__('Order By', 'qode-tours'),
					'param_name'  => 'orderby',
					'value'       => array(
						esc_html__('Menu Order', 'qode-tours') => 'menu_order',
						esc_html__('Title', 'qode-tours')      => 'title',
						esc_html__('Date', 'qode-tours')       => 'date'
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
					'heading'     => esc_html__('Number of Destinations Per Page', 'qode-tours'),
					'param_name'  => 'number',
					'value'       => '-1',
					'description' => esc_html__('Enter -1 to show all', 'qode-tours'),
					'group'       => esc_html__('Query Options', 'qode-tours')
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__('Show Only Destinations with Listed IDs', 'qode-tours'),
					'param_name'  => 'selected_destinations',
					'description' => esc_html__('Delimit ID numbers by comma (leave empty for all)', 'qode-tours'),
					'group'       => esc_html__('Query Options', 'qode-tours')
				)
			)
		));
	}

	public function render($atts, $content = null) {
		$args = array(
			'number_of_cols'		=> '3',
			'space_between_items'	=> 'normal',
			'image_size'			=> 'full',
			'custom_image_dimensions'=> '',
			'title_tag'				=> 'h3',
			'orderby'               => 'date',
			'order'                 => 'DESC',
			'number'                => '-1',
			'selected_destinations' => ''
		);

		$params = shortcode_atts($args, $atts);

		$query = $this->buildQueryObject($params);

		$params['query']  = $query;
		$params['caller'] = $this;

		$params['thumb_size'] = qode_tours_get_image_size_param($params);
		$params['classes'] = $this->gridClasses($params);

		return qode_tours_get_tour_module_template_part('destination-grid/templates/destination-grid-template', 'destinations', 'shortcodes', '', $params);
	}

	private function buildQueryObject($params) {
		$queryArray['post_status'] = 'publish';
		$queryArray['post_type'] = 'destinations';

		if(!empty($params['orderby'])) {
			$queryArray['orderby'] = $params['orderby'];
		}

		if(!empty($params['order'])) {
			$queryArray['order'] = $params['order'];
		}

		if(!empty($params['number'])) {
			$queryArray['posts_per_page'] = $params['number'];
		}

		$toursIds = null;
		if(!empty($params['selected_destinations'])) {
			$toursIds               = explode(',', $params['selected_destinations']);
			$queryArray['post__in'] = $toursIds;
		}

		return new \WP_Query($queryArray);
	}
	

	private function gridClasses($params) {
		$classes = array();
		$classes[] = 'qode-tours-destination-holder';
		$classes[] = 'qode-tours-row';

		if ($params['space_between_items'] !== ''){
			$classes[] = 'qode-'.$params['space_between_items'].'-space';
		}

		if ($params['number_of_cols'] !== '') {
			$classes[] = 'qode-tours-columns-'.$params['number_of_cols'];
		}

		return implode(' ', $classes);		
	}
}