<?php

namespace QodeTours\CPT\Tours\Shortcodes;

use QodeTours\Lib\ShortcodeInterface;

class ToursFilter implements ShortcodeInterface {
	private $base;

	/**
	 * ToursFilter constructor.
	 */
	public function __construct() {
		$this->base = 'qode_tours_filter';

		add_action('vc_before_init_vc', array($this, 'vcMap'));
	}

	public function vcMap() {
		vc_map(array(
			'name'                      => esc_html__('Tours Filters', 'qode-tours'),
			'base'                      => $this->base,
			'category'       			=> esc_html__('by QODE TOURS', 'qode-tours'),
			'icon'                      => 'icon-wpb-tours-filters extended-custom-icon-qode',
			'allowed_container_element' => 'vc_row',
			'params'                    => array(
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__('Type', 'qode-tours'),
					'param_name'  => 'filter_type',
					'value'       => array(
						esc_html__('Vertical', 'qode-tours')   => 'vertical',
						esc_html__('Horizontal', 'qode-tours') => 'horizontal'
					),
					'save_always' => true,
					'admin_label' => true
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__('Skin', 'qode-tours'),
					'param_name'  => 'vertical_filter_skin',
					'value'       => array(
						esc_html__('Grey', 'qode-tours')  => 'grey',
						esc_html__('White', 'qode-tours') => 'white'
					),
					'dependency'  => array('element' => 'filter_type', 'value' => 'vertical')
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__('Skin', 'qode-tours'),
					'param_name'  => 'horizontal_filter_skin',
					'value'       => array(
						esc_html__('Light', 'qode-tours') => 'light',
						esc_html__('Dark', 'qode-tours')  => 'dark'
					),
					'dependency'  => array('element' => 'filter_type', 'value' => 'horizontal')
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__('Filter Full Width', 'qode-tours'),
					'param_name'  => 'filter_full_width',
					'value'       => array(
						esc_html__('Yes', 'qode-tours') => 'yes',
						esc_html__('No', 'qode-tours')  => 'no'
					),
					'dependency'  => array('element' => 'filter_type', 'value' => 'horizontal')
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__('Filter Semi-Transparent', 'qode-tours'),
					'param_name'  => 'filter_semitransparent',
					'value'       => array(
						esc_html__('Yes', 'qode-tours') => 'yes',
						esc_html__('No', 'qode-tours')  => 'no'
					),
					'dependency'  => array('element' => 'filter_type', 'value' => 'horizontal')
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__('Show Tour Types Checkboxes', 'qode-tours'),
					'param_name'  => 'show_tour_types',
					'value'       => array(
						esc_html__('Yes', 'qode-tours') => 'yes',
						esc_html__('No', 'qode-tours')  => 'no'
					),
					'dependency'  => array('element' => 'filter_type', 'value' => 'vertical')
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__('Number of Tour Types', 'qode-tours'),
					'param_name'  => 'number_of_tour_types',
					'dependency'  => array('element' => 'filter_type', 'value' => 'vertical', 'element' => 'show_tour_types', 'value' => 'yes')
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__('Enable Filter Shadow', 'qode-tours'),
					'param_name'  => 'filter_shadow',
					'value'       => array(
						esc_html__('Yes', 'qode-tours') => 'yes',
						esc_html__('No', 'qode-tours')  => 'no'
					),
					'dependency'  => array('element' => 'filter_type', 'value' => 'horizontal')
				),
			)
		));
	}

	public function getBase() {
		return $this->base;
	}

	public function render($atts, $content = null) {
		$args = array(
			'filter_type'            => 'vertical',
			'vertical_filter_skin'   => 'grey',
			'horizontal_filter_skin' => 'light',
			'show_tour_types'        => 'yes',
			'filter_full_width'      => 'yes',
			'filter_semitransparent' => 'yes',
			'number_of_tour_types'   => '',
			'filter_shadow'			 => 'yes'
		);

		$params = shortcode_atts($args, $atts);

		$filterClass = array(
			'qode-tours-filter-holder',
			'qode-tours-filter-'.$params['filter_type']
		);

		switch($params['filter_type']) {
			case 'vertical':
				$filterClass[] = 'qode-tours-filter-skin-'.$params['vertical_filter_skin'];
				break;
			case 'horizontal':
				$filterClass[] = 'qode-tours-filter-skin-'.$params['horizontal_filter_skin'];
				break;
		}


		$params['show_tour_types'] = $params['show_tour_types'] === 'yes';

		$params['display_container_inner'] = $params['filter_full_width'] === 'no' && $params['filter_type'] === 'horizontal';

		if($params['filter_semitransparent'] === 'yes') {
			$filterClass[] = 'qode-tours-filter-semitransparent';
		}

		if($params['display_container_inner']) {
			$filterClass[] = 'qode-tours-full-width-filter';
		}

		if($params['filter_shadow'] === 'yes'){
			$filterClass[] = 'qode-tours-filter-with-shadow';
		}

		$params['filter_class'] = $filterClass;

		return qode_tours_get_tour_module_template_part('tours-filter/templates/tours-filters-holder', 'tours', 'shortcodes', '', $params);
	}
}