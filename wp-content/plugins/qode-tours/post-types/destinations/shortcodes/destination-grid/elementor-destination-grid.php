<?php

class QodeToursElementorDestinationGrid extends \Elementor\Widget_Base{
	public function get_name() {
		return 'qode_destination_grid';
	}
	
	public function get_title() {
		return esc_html__( 'Destinations Grid', 'qode-tours' );
	}
	
	public function get_icon() {
		return 'bridge-elementor-custom-icon bridge-elementor-destination-grid';
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
			'number_of_cols',
			[
				'label' => esc_html__('Number of Columns', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5'	=> '5',
					'6'	=> '6'
				],
				'default' => '3'
			]
		);
		
		$this->add_control(
			'image_size',
			[
				'label' => esc_html__('Image Proportions', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					''          => esc_html__('Default', 'qode-tours'),
					'full'      => esc_html__('Original', 'qode-tours'),
					'square'    => esc_html__('Square', 'qode-tours'),
					'landscape' => esc_html__('Landscape', 'qode-tours'),
					'portrait'  => esc_html__('Portrait', 'qode-tours'),
					'custom'    => esc_html__('Custom', 'qode-tours')
				],
				'default' => ''
			]
		);
		
		$this->add_control(
			'custom_image_dimensions',
			[
				'label' => esc_html__('Image Dimensions', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__('Enter custom image dimensions. Enter image size in pixels: 200x100 (Width x Height)', 'qode-tours'),
				'condition' => [
					'image_size' => 'custom'
				]
			]
		);
		
		$this->add_control(
			'space_between_items',
			[
				'label' => esc_html__('Space Between Items', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'normal'  => esc_html__('Medium', 'qode-tours'),
					'small'   => esc_html__('Small', 'qode-tours'),
					'tiny'    => esc_html__('Tiny', 'qode-tours'),
					'no'      => esc_html__('None', 'qode-tours'),
				],
				'default' => 'normal'
			]
		);
		
		$this->add_control(
			'title_tag',
			[
				'label' => esc_html__('Title Tag', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => bridge_qode_get_title_tag( true ),
				'default' => 'h5'
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'query',
			[
				'label' => esc_html__( 'Query Options', 'qode-tours' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'order_by',
			[
				'label' => esc_html__('Order By', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'menu_order'  => esc_html__('Menu Order', 'qode-tours'),
					'title'       => esc_html__('Title', 'qode-tours'),
					'date'        => esc_html__('Date', 'qode-tours'),
				],
				'default' => 'date'
			]
		);
		
		$this->add_control(
			'order',
			[
				'label' => esc_html__('Order', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'ASC'       => esc_html__('ASC', 'qode-tours'),
					'DESC'      => esc_html__('DESC', 'qode-tours')
				],
				'default' => 'ASC'
			]
		);
		
		$this->add_control(
			'number',
			[
				'label' => esc_html__('Number of Destinations Per Page', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__('(Enter -1 to show all)', 'qode-tours'),
			]
		);
		
		$this->add_control(
			'selected_destinations',
			[
				'label' => esc_html__('Show Only Destinations with Listed IDs', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__('Delimit ID numbers by comma (leave empty for all', 'qode-tours'),
			]
		);
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$params = $this->get_settings_for_display();
		
		extract($params);
		
		$query = $this->buildQueryObject($params);
		
		$html = '';
		
		$params['query']  = $query;
		$params['caller'] = $this;
		
		$params['thumb_size'] = qode_tours_get_image_size_param($params);
		$params['classes'] = $this->gridClasses($params);
		
		$html .= qode_tours_get_tour_module_template_part('destination-grid/templates/destination-grid-template', 'destinations', 'shortcodes', '', $params);
		echo bridge_qode_get_module_part($html);
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

\Elementor\Plugin::instance()->widgets_manager->register( new QodeToursElementorDestinationGrid() );