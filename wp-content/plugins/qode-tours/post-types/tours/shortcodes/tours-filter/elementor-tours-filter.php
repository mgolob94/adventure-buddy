<?php

class QodeToursElementorToursFilter extends \Elementor\Widget_Base{
	public function get_name() {
		return 'qode_tours_filter';
	}
	
	public function get_title() {
		return esc_html__( 'Tours Filters', 'qode-tours' );
	}
	
	public function get_icon() {
		return 'bridge-elementor-custom-icon bridge-elementor-tours-filters';
	}
	
	public function get_categories() {
		return [ 'qode-tours' ];
	}
	
	protected function register_controls() {
		
		$this->start_controls_section(
			'design',
			[
				'label' => esc_html__( 'Design Options', 'qode-tours' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'filter_type',
			[
				'label' => esc_html__('Type', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'vertical'      => esc_html__('Vertical', 'qode-tours'),
					'horizontal'    => esc_html__('Horizontal', 'qode-tours')
				],
				'default' => 'horizontal'
			]
		);
		
		$this->add_control(
			'vertical_filter_skin',
			[
				'label' => esc_html__('Skin', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'grey'  => 'Grey',
					'white' => 'White'
				],
				'condition' => [
					'filter_type' => 'vertical'
				]
			]
		);
		
		$this->add_control(
			'horizontal_filter_skin',
			[
				'label' => esc_html__('Skin', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'light'  => 'Light',
					'dark'   => 'Dark'
				],
				'condition' => [
					'filter_type' => 'horizontal'
				]
			]
		);
		
		$this->add_control(
			'filter_full_width',
			[
				'label' => esc_html__('Filter Full Width', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'yes'  => 'Yes',
					'no'   => 'No'
				],
				'condition' => [
					'filter_type' => 'horizontal'
				]
			]
		);
		
		$this->add_control(
			'filter_semitransparent',
			[
				'label' => esc_html__('Filter Semi-Transparent', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'yes'  => 'Yes',
					'no'   => 'No'
				],
				'condition' => [
					'filter_type' => 'horizontal'
				]
			]
		);
		
		$this->add_control(
			'show_tour_types',
			[
				'label' => esc_html__('Show Tour Types Checkboxes', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'yes'  => 'Yes',
					'no'   => 'No'
				],
				'condition' => [
					'filter_type' => 'vertical'
				]
			]
		);
		
		$this->add_control(
			'number_of_tour_types',
			[
				'label' => esc_html__('Number of Tour Types', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__('Enter custom image dimensions. Enter image size in pixels: 200x100 (Width x Height)', 'qode-tours'),
				'condition' => [
					'filter_type'     => 'vertical',
					'show_tour_types' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'filter_shadow',
			[
				'label' => esc_html__('Enable Filter Shadow', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'yes'  => 'Yes',
					'no'   => 'No'
				],
				'condition' => [
					'filter_type' => 'horizontal'
				]
			]
		);
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$params = $this->get_settings_for_display();
		
		extract($params);
		
		$html = '';
		
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

		$html .= qode_tours_get_tour_module_template_part('tours-filter/templates/tours-filters-holder', 'tours', 'shortcodes', '', $params);
		echo bridge_qode_get_module_part($html);
	}
	
}

\Elementor\Plugin::instance()->widgets_manager->register( new QodeToursElementorToursFilter() );