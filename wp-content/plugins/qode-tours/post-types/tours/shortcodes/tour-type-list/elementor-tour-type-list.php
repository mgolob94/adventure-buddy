<?php

class QodeToursElementorTourTypeList extends \Elementor\Widget_Base{
	public function get_name() {
		return 'qode_tour_type_list';
	}
	
	public function get_title() {
		return esc_html__( 'Tour Type List', 'qode-tours' );
	}
	
	public function get_icon() {
		return 'bridge-elementor-custom-icon bridge-elementor-tour-type-list';
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
			'number',
			[
				'label' => esc_html__('Number of Tour Types', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::TEXT
			]
		);
		
		$this->add_control(
			'orderby',
			[
				'label' => esc_html__('Order By', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'name'       => esc_html__('Name', 'qode-tours'),
					'count'      => esc_html__('Count', 'qode-tours')
				]
			]
		);
		
		$this->add_control(
			'order',
			[
				'label' => esc_html__('Order Type', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'ASC'       => esc_html__('ASC', 'qode-tours'),
					'DESC'      => esc_html__('DESC', 'qode-tours')
				],
				'default' => 'ASC'
			]
		);
		
		$this->add_control(
			'hover_color',
			[
				'label' => esc_html__('Choose Hover Color', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'white'       => esc_html__('White', 'qode-tours'),
					'gray'        => esc_html__('Gray', 'qode-tours')
				]
			]
		);
		
		$this->add_control(
			'split_two_cols',
			[
				'label' => esc_html__('Split in Two Columns', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => bridge_qode_get_yes_no_select_array(),
				'default' => 'no'
			]
		);
		
		$this->end_controls_section();
		
	}
	
	protected function render(){
		$params = $this->get_settings_for_display();
		
		extract($params);
		
		$html = '';
		
		$tour_types = $this->getTourTypes($params);
		
		$params['tour_types'] = $tour_types;
		$params['caller']     = $this;
		$params['list_classes']  = $this->getListClasses($params);
		
		$html .= qode_tours_get_tour_module_template_part('tour-type-list/templates/tour-type-list', 'tours', 'shortcodes', '', $params);
		echo bridge_qode_get_module_part($html);
	}
	
	private function getTourTypes($params) {
		$defaults = array(
			'taxonomy' => 'tour-category',
			'number'   => '',
			'orderby'  => 'name',
			'order'    => 'ASC'
		);
		
		$query_args = wp_parse_args($params, $defaults);
		
		return get_terms($query_args);
	}
	
	public function getTypeIcon($tour_type) {
		$type_image_id = get_term_meta($tour_type->term_id, 'tours_category_custom_image', true);
		$image_original = wp_get_attachment_image_src( $type_image_id, 'full' );
		$type_image = $image_original[0];
		
		if(!empty($type_image)) {
			return '<img src="'.esc_url($type_image).'" alt="term-custom-icon">';
		}
		
		if(!qode_tours_theme_installed()) {
			return false;
		}
		
		$category_icon_pack = get_term_meta($tour_type->term_id, 'tours_category_icon', true);
		$icon_param_name    = bridge_qode_icon_collections()->getIconCollectionParamNameByKey($category_icon_pack);
		$category_icon      = get_term_meta($tour_type->term_id, 'tours_category_icon_'.$icon_param_name, true);
		
		if(empty($category_icon)) {
			return '';
		}
		
		return bridge_qode_icon_collections()->getIconHTML($category_icon, $category_icon_pack);
	}
	
	public function getTypeMinPrice($tour_type) {
		global $wpdb;
		
		if(qode_tours_is_wpml_installed()) {
			$lang = ICL_LANGUAGE_CODE;
			
			$sql = "SELECT MIN(CAST(pm.meta_value AS UNSIGNED)) AS min_price
					FROM {$wpdb->prefix}postmeta pm
					LEFT JOIN {$wpdb->prefix}posts p ON p.ID = pm.post_id
					LEFT JOIN {$wpdb->prefix}term_relationships tr ON tr.object_id = p.ID
					LEFT JOIN {$wpdb->prefix}icl_translations icl_t ON icl_t.element_id = p.ID
					WHERE pm.meta_key = 'qode_tours_price'
					AND tr.term_taxonomy_id = %d
					AND icl_t.language_code='$lang'";
		} else {
			$sql = "SELECT MIN(CAST(pm.meta_value AS UNSIGNED)) AS min_price
					FROM {$wpdb->prefix}postmeta pm
					LEFT JOIN {$wpdb->prefix}posts p ON p.ID = pm.post_id
					LEFT JOIN {$wpdb->prefix}term_relationships tr ON tr.object_id = p.ID
					WHERE pm.meta_key = 'qode_tours_price'
					AND tr.term_taxonomy_id = %d";
		}
		
		$results = $wpdb->get_results($wpdb->prepare($sql, $tour_type->term_id));
		
		if(!(is_array($results) && count($results))) {
			return false;
		}
		
		$result_instance = array_shift($results);
		
		return $result_instance->min_price;
	}
	
	private function getListClasses($params){
		$list_classes = array();
		$list_classes[] = 'qode-tours-row';
		$list_classes[] = 'qode-tr-normal-space-lr-only';
		
		if ($params['split_two_cols'] == 'yes'){
			$list_classes[] = 'qode-tours-columns-2';
		}
		
		$params['hover_color'] == 'white' ? $list_classes[] = 'qode-tours-white-hover' : $list_classes[] = 'qode-tours-gray-hover';
		
		return implode(' ', $list_classes);
	}
}

\Elementor\Plugin::instance()->widgets_manager->register( new QodeToursElementorTourTypeList() );