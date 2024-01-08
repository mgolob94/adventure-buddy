<?php

namespace QodeTours\CPT\Tours\Shortcodes;

use QodeTours\Lib\ShortcodeInterface;

class TourTypeList implements ShortcodeInterface {
    private $base;

    /**
     * TourTypeList constructor.
     */
    public function __construct() {
        $this->base = 'qode_tour_type_list';
	    
        add_action('vc_before_init', array($this, 'vcMap'));
    }

    public function getBase() {
        return $this->base;
    }

    public function vcMap() {
        vc_map(array(
            'name'                      => esc_html__('Tour Type List', 'qode-tours'),
            'base'                      => $this->base,
			'category'        			=> esc_html__('by QODE TOURS', 'qode-tours'),
            'icon'                      => 'icon-wpb-tour-type-list extended-custom-icon-qode',
            'allowed_container_element' => 'vc_row',
            'params'                    => array(
                array(
                    'type'        => 'textfield',
                    'heading'     => esc_html__('Number of Tour Types', 'qode-tours'),
                    'param_name'  => 'number'
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => esc_html__('Order By', 'qode-tours'),
                    'param_name'  => 'orderby',
                    'value'       => array(
	                    esc_html__('Name', 'qode-tours')  => 'name',
	                    esc_html__('Count', 'qode-tours') => 'count'
                    )
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => esc_html__('Order Type', 'qode-tours'),
                    'param_name'  => 'order',
                    'value'       => array(
					    esc_html__('ASC', 'qode-tours')  => 'ASC',
					    esc_html__('DESC', 'qode-tours') => 'DESC'
                    )
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => esc_html__('Choose Hover Color', 'qode-tours'),
                    'param_name'  => 'hover_color',
                    'value'       => array(
	                    esc_html__('White', 'qode-tours') => 'white',
	                    esc_html__('Gray', 'qode-tours')  => 'gray'
                    )
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => esc_html__('Split in Two Columns', 'qode-tours'),
                    'param_name'  => 'split_two_cols',
                    'value'       => array(
					    esc_html__('No', 'qode-tours')  => 'no',
					    esc_html__('Yes', 'qode-tours') => 'yes'
                    ),
	                'save_always' => true
                ),
            )
        ));
    }

    public function render($atts, $content = null) {
        $args = array(
            'number'          => '',
            'orderby'         => 'name',
            'order'           => 'ASC',
            'hover_color'     => 'white',
            'split_two_cols'  => ''
        );

        $params = shortcode_atts($args, $atts);

        $tour_types = $this->getTourTypes($params);

        $params['tour_types'] = $tour_types;
        $params['caller']     = $this;
        $params['list_classes']  = $this->getListClasses($params);

        return qode_tours_get_tour_module_template_part('tour-type-list/templates/tour-type-list', 'tours', 'shortcodes', '', $params);
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