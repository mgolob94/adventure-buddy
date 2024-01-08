<?php
namespace QodeTours\CPT\Tours\Shortcodes;

use QodeTours\CPT\Tours\Lib\ToursQuery;
use QodeTours\Lib\ShortcodeInterface;

class ToursCarousel implements ShortcodeInterface {
	private $base;

	/**
	 * ToursCarousel constructor.
	 */
	public function __construct() {
		$this->base = 'qode_tours_carousel';

		add_action('vc_before_init', array($this, 'vcMap'));
	}


	/**
	 * Returns base for shortcode
	 * @return string
	 */
	public function getBase() {
		return $this->base;
	}

	public function vcMap() {
		vc_map(array(
			'name'                      => esc_html__('Tours Carousel', 'qode-tours'),
			'base'                      => $this->base,
			'category'       			=> esc_html__('by QODE TOURS', 'qode-tours'),
			'icon'                      => 'icon-wpb-tours-carousel extended-custom-icon-qode',
			'allowed_container_element' => 'vc_row',
			'params'                    => array_merge(
				array(
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__('Tours List Type', 'qode-tours'),
						'param_name'  => 'tour_type',
						'value'       => array(
							esc_html__('Standard', 'qode-tours') => 'standard',
							esc_html__('Gallery', 'qode-tours')  => 'gallery'
						),
						'admin_label' => true,
						'description' => esc_html__('Default value is Standard', 'qode-tours'),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__('Number of visible Tours', 'qode-tours'),
						'param_name'  => 'tour_number',
						'value'       => array(
							esc_html__('Two', 'qode-tours') => '2',
							esc_html__('Three', 'qode-tours')  => '3',
							esc_html__('Four', 'qode-tours')  => '4'
						),
						'group'		  => 'Query Options'
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__('Image Proportions', 'qode-tours'),
						'param_name'  => 'image_size',
						'value'       => array(
							esc_html__('Original', 'qode-tours')  => 'full',
							esc_html__('Square', 'qode-tours')    => 'square',
							esc_html__('Landscape', 'qode-tours') => 'landscape',
							esc_html__('Portrait', 'qode-tours')  => 'portrait',
							esc_html__('Custom', 'qode-tours')    => 'custom'
						)
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
						'type'        => 'textfield',
						'heading'     => esc_html__('Text Length', 'qode-tours'),
						'param_name'  => 'text_length',
						'description' => esc_html__('Number of words', 'qode-tours')
					),
				),
				qode_tours_query()->queryVCParams()
			) //close array_merge
		));
	}

	/**
	 * Renders shortcodes HTML
	 *
	 * @param $atts array of shortcode params
	 * @param $content string shortcode content
	 *
	 * @return string
	 */
	public function render($atts, $content = null) {
		$args = array(
			'tour_type'                     => 'standard',
			'tour_number'					=> '3',
			'image_size'                    => 'full',
			'custom_image_dimensions'       => '',
			'space_between_items'			=> 'normal',
			'title_tag'                     => 'h4',
			'text_length'                   => '90'
		);

		$args   = array_merge($args, qode_tours_query()->getShortcodeAtts());
		$params = shortcode_atts($args, $atts);
		
		if(!empty($params['destination'])) {
			$destination_query = new \WP_Query(array('post_status' => 'published', 'post_type' => 'destinations', 'name' => esc_attr(strtolower($params['destination']))));
			wp_reset_postdata();
			$destination_id = $destination_query->posts[0]->ID;
			
			$query = qode_tours_query()->buildQueryObject($params, array(
				'meta_key' => 'qode_tours_destination',
				'meta_value' => esc_attr($destination_id)
			));
		} else {
			$query  = qode_tours_query()->buildQueryObject($params);
		}

		$params['query']  = $query;
		$params['caller'] = $this;

		$params['thumb_size'] = qode_tours_get_image_size_param($params);
		$params['carousel_data'] = $this->getSliderData($params);
		$params['list_classes'] = $this->getListClasses($params);

		return qode_tours_get_tour_module_template_part('tours-carousel/templates/tours-carousel-holder', 'tours', 'shortcodes', '', $params);
	}

	public function getItemTemplate($tour_type = 'standard', $params = array()) {
		echo qode_tours_get_tour_module_template_part('templates/tour-item/'.$tour_type, 'tours', '', '', $params);
	}

	private function getSliderData( $params ) {
		$slider_data = array();
		
		if(!empty($params['tour_number'])){
			$slider_data['data-number-of-items']        = $params['tour_number'];
		}

		else {
			$slider_data['data-number-of-items']        = '4';
		}

		$slider_data['data-enable-loop']            = 'yes';
		$slider_data['data-enable-navigation']      = 'yes';
		$slider_data['data-enable-pagination']      = 'no';
		
		return $slider_data;
	}

	private function getListClasses( $params ) {
		$list_classes = array();
		$list_classes[] = 'qode-tours-row';

		if ($params['space_between_items'] !== ''){
			$list_classes[] = 'qode-'.$params['space_between_items'].'-space';
		}

		return implode(' ', $list_classes);
	}
}