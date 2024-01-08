<?php

class QodeToursElementorToursCarousel extends \Elementor\Widget_Base{
	public function get_name() {
		return 'qode_tours_carousel';
	}
	
	public function get_title() {
		return esc_html__( 'Tours Carousel', 'qode-tours' );
	}
	
	public function get_icon() {
		return 'bridge-elementor-custom-icon bridge-elementor-tours-carousel';
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
			'tour_type',
			[
				'label' => esc_html__('Tours List Type', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'standard'   => esc_html__('Standard', 'qode-tours'),
					'gallery'    => esc_html__('Gallery', 'qode-tours')
				],
				'default' => 'standard',
				'description' => esc_html__('Default value is Standard', 'qode-tours'),
			]
		);
		
		$this->add_control(
			'image_size',
			[
				'label' => esc_html__('Image Proportions', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'full'      => esc_html__('Original', 'qode-tours'),
					'square'    => esc_html__('Square', 'qode-tours'),
					'landscape' => esc_html__('Landscape', 'qode-tours'),
					'portrait'  => esc_html__('Portrait', 'qode-tours'),
					'custom'    => esc_html__('Custom', 'qode-tours')
				],
				'default' => 'full',
				'condition' => [
					'tour_type' => ['standard', 'gallery']
				]
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
					'normal'   => esc_html__('Medium', 'qode-tours'),
					'small'    => esc_html__('Small', 'qode-tours'),
					'tiny'     => esc_html__('Tiny', 'qode-tours'),
					'no'       => esc_html__('None', 'qode-tours')
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
		
		$this->add_control(
			'text_length',
			[
				'label' => esc_html__('Text Length', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__('Number of words', 'qode-tours'),
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
			'tour_number',
			[
				'label' => esc_html__('Number of visible Tours', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'2' => 'Two',
					'3' => 'Three',
					'4' => 'Four'
				],
				'default' => '3'
			]
		);
		
		$this->add_control(
			'number',
			[
				'label' => esc_html__('Number of Tours Per Page', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__('Enter -1 to show all', 'qode-tours'),
			]
		);
		
		$this->add_control(
			'order_by',
			[
				'label' => esc_html__('Order By', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'title'       => esc_html__('Title', 'qode-tours'),
					'date'        => esc_html__('Date', 'qode-tours'),
					'menu_order'  => esc_html__('Menu Order', 'qode-tours'),
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
			'tour_category',
			[
				'label' => esc_html__('Tour Category', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__('Enter one tour category slug (leave empty for showing all categories)', 'qode-tours'),
			]
		);
		
		$this->add_control(
			'selected_tours',
			[
				'label' => esc_html__('Show Only Tours with Listed IDs', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__('Delimit ID numbers by comma (leave empty for all)', 'qode-tours'),
			]
		);
		
		$this->add_control(
			'destination',
			[
				'label' => esc_html__('Destination Name', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__('Display tour items from filled destination', 'qode-tours'),
			]
		);
		
		$this->end_controls_section();
		
	}
	
	protected function render(){
		$params = $this->get_settings_for_display();
		
		extract($params);
		
		$html = '';
		
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
		
		$html .= qode_tours_get_tour_module_template_part('tours-carousel/templates/tours-carousel-holder', 'tours', 'shortcodes', '', $params);
		echo bridge_qode_get_module_part($html);
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

\Elementor\Plugin::instance()->widgets_manager->register( new QodeToursElementorToursCarousel() );