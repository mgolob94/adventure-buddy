<?php

class QodeToursElementorToursList extends \Elementor\Widget_Base{
	public function get_name() {
		return 'qode_tours_list';
	}
	
	public function get_title() {
		return esc_html__( 'Tours List', 'qode-tours' );
	}
	
	public function get_icon() {
		return 'bridge-elementor-custom-icon bridge-elementor-tours-list';
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
				'label' => esc_html__('Image Proportions', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'standard'   => esc_html__('Standard', 'qode-tours'),
					'gallery'    => esc_html__('Gallery', 'qode-tours'),
					'masonry'    => esc_html__('Masonry', 'qode-tours')
				],
				'default' => 'standard',
				'description' => esc_html__('Default value is Standard', 'qode-tours'),
			]
		);
		
		$this->add_control(
			'tour_item',
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
			'hover_color',
			[
				'label' => esc_html__('Hover Background Overlay', 'qode-music'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'tour_type' => 'masonry'
				]
			]
		);
		
		$this->add_control(
			'enable_shadow',
			[
				'label' => esc_html__('Enable Shadow', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => bridge_qode_get_yes_no_select_array(),
				'default' => 'no'
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
				'options' => bridge_qode_get_space_between_items_array()
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
		
		$this->add_control(
			'filter',
			[
				'label' => esc_html__('Enable Category Filter', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => bridge_qode_get_yes_no_select_array(),
				'default' => 'no',
				'description' => esc_html__('Default value is No', 'qode-tours'),
			]
		);
		
		$this->add_control(
			'enable_load_more',
			[
				'label' => esc_html__('Enable Load More', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => bridge_qode_get_yes_no_select_array(),
				'default' => 'no',
				'description' => esc_html__('Default value is No', 'qode-tours'),
			]
		);
		
		$this->add_control(
			'load_more_text',
			[
				'label' => esc_html__('Load More Button Text', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__('Default text is "Load More', 'qode-tours'),
				'condition' => [
					'enable_load_more' => 'yes'
				]
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
		
		$meta_params = $this->getMetaQueryParams($params);
		$query  = qode_tours_query()->buildQueryObject($params,$meta_params);
		
		$params['query']  = $query;
		$params['caller'] = $this;
		
		$params['thumb_size'] = qode_tours_get_image_size_param($params);
		
		if($params['filter'] == 'yes') {
			$params['filter_categories'] = $this->getFilterCategories($params);
		}
		
		$params['list_classes']			   = $this->getListClasses($params);
		$params['enable_load_more']        = $params['enable_load_more'] === 'yes';
		$params['load_more_text']          = empty($params['load_more_text']) ? esc_html__('Load More', 'qode-tours') : $params['load_more_text'];
		$params['display_load_more_data']  = (int) $params['number'] == $params['number'] && $params['number'] > 0;
		$params['background_hover_color']  = $this->getHoverBackground($params);
		$params['description_border_color']  = $this->getDescriptionItemBorderColor($params);
		
		$html .= qode_tours_get_tour_module_template_part('tours-list/templates/tours-list-holder', 'tours', 'shortcodes', '', $params);
		echo bridge_qode_get_module_part($html);
	}
	
	public function getMetaQueryParams($params){
		$meta_params = array();
		
		if(!empty($params['destination'])) {
			$destination_query = new \WP_Query(array('post_status' => 'published', 'post_type' => 'destinations', 'name' => esc_attr(strtolower($params['destination']))));
			wp_reset_postdata();
			$destination_id = $destination_query->posts[0]->ID;
			
			$meta_params = array(
				'meta_key' => 'qode_tours_destination',
				'meta_value' => esc_attr($destination_id)
			);
		}
		
		return $meta_params;
	}
	
	
	public function handleLoadMore() {
		$fields = $this->parseRequest();
		
		$returnObject = new \stdClass();
		
		$meta_params = $this->getMetaQueryParams($fields);
		$query  = qode_tours_query()->buildQueryObject($fields,$meta_params);
		
		if($query->have_posts()) {
			ob_start();
			
			$this->getToursQueryTemplate(array(
				'query'     => $query,
				'tour_type' => $fields['tour_type'],
				'caller'    => $this,
				'params'    => $fields
			));
			
			$returnObject->html      = ob_get_clean();
			$returnObject->havePosts = true;
			$returnObject->message   = esc_html__('Success','qode-tours');
			$returnObject->nextPage  = $fields['next_page'] + 1;
		} else {
			$returnObject->havePosts = false;
			$returnObject->message   = esc_html__('No more tours.', 'qode-tours');
		}
		
		echo json_encode($returnObject);
		exit;
	}
	
	private function parseRequest() {
		if(empty($_POST['fields'])) {
			return false;
		}
		
		parse_str($_POST['fields'], $fields);
		
		if(!(is_array($fields) && count($fields))) {
			return false;
		}
		
		return $fields;
	}
	
	public function getItemTemplate($tour_type = 'standard', $params = array()) {
		$slug = $tour_type == 'masonry' ? get_post_meta(get_the_ID(), 'qode_tours_masonry_layout', true) : "";
		echo qode_tours_get_tour_module_template_part('templates/tour-item/'.$tour_type, 'tours', '', $slug, $params);
	}
	
	public function getFilterCategories($params) {
		$cat_id       = 0;
		$top_category = '';
		
		if(!empty($params['tour_category'])) {
			$top_category = get_term_by('slug', $params['tour_category'], 'tour-category');
			if(isset($top_category->term_id)) {
				$cat_id = $top_category->term_id;
			}
		}
		
		$args = array(
			'taxonomy' => 'tour-category',
			'child_of' => $cat_id,
		);
		
		$filter_categories = get_terms($args);
		
		return $filter_categories;
	}
	
	public function getToursQueryTemplate($params) {
		echo qode_tours_get_tour_module_template_part('tours-list/templates/tours-list-loop', 'tours', 'shortcodes', '', $params);
	}
	
	private function getListClasses($params) {
		$list_classes = array();
		$list_classes[] = 'qode-tours-list-holder';
		$list_classes[] = 'qode-tours-row';
		
		$list_classes[] = ! empty( $params['space_between_items'] ) ? 'qode-' . $params['space_between_items'] . '-space' : 'qode-normal-space';
		$list_classes[] = ! empty( $params['tour_type'] ) && $params['tour_type'] === 'masonry' ? ''/*'qode-disable-bottom-space qode-disable-item-bottom-space'*/ : '';
		
		if ($params['tour_item']) {
			$list_classes[] = 'qode-tours-columns-'.$params['tour_item'];
		}
		
		if ($params['tour_type']) {
			$list_classes[] = 'qode-tours-type-'.$params['tour_type'];
		}
		
		if($params['enable_shadow'] == 'yes'){
			$list_classes[] = 'qode-tours-list-with-shadow';
		}
		
		return implode(' ', $list_classes);
	}
	
	private function getHoverBackground($params){
		$style = array();
		
		if(!empty($params['hover_color'])){
			$style[] ='background-color: '.$params['hover_color'];
		}
		
		return $style;
	}
	
	private function getDescriptionItemBorderColor($params){
		$style = array();
		
		if(!empty($params['hover_color'])){
			$style[] ='border-color: '.$params['hover_color'];
		}
		
		return $style;
	}
}

\Elementor\Plugin::instance()->widgets_manager->register( new QodeToursElementorToursList() );