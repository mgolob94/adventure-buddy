<?php
namespace QodeTours\CPT\Tours\Shortcodes;

use QodeTours\CPT\Tours\Lib\ToursQuery;
use QodeTours\Lib\ShortcodeInterface;

class ToursList implements ShortcodeInterface {
	private $base;

	/**
	 * ToursCarousel constructor.
	 */
	public function __construct() {
		$this->base = 'qode_tours_list';

		add_action('vc_before_init', array($this, 'vcMap'));

		add_action('wp_ajax_nopriv_qode_tours_list_ajax_pagination', array($this, 'handleLoadMore'));
		add_action('wp_ajax_qode_tours_list_ajax_pagination', array($this, 'handleLoadMore'));
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
			'name'                      => esc_html__('Tours List', 'qode-tours'),
			'base'                      => $this->base,
			'category'        			=> esc_html__('by QODE TOURS', 'qode-tours'),
			'icon'                      => 'icon-wpb-tours-list extended-custom-icon-qode',
			'allowed_container_element' => 'vc_row',
			'params'                    => array_merge(
				array(
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__('Tours List Type', 'qode-tours'),
						'param_name'  => 'tour_type',
						'value'       => array(
							esc_html__('Standard', 'qode-tours') => 'standard',
							esc_html__('Gallery', 'qode-tours')  => 'gallery',
							esc_html__('Masonry', 'qode-tours')  => 'masonry'
						),
						'admin_label' => true,
						'save_always' => true,
						'description' => esc_html__('Default value is Standard', 'qode-tours'),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__('Number of Columns', 'qode-tours'),
						'param_name'  => 'tour_item',
						'value'       => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6'
						),
						'save_always' => true,
					),
					array(
						'type'		=> 'colorpicker',
						'heading'	=> esc_html__('Hover Background Overlay','qode-tours'),
						'param_name'=> 'hover_color',
						'dependency'=> array('element' => 'tour_type', 'value' => 'masonry')
					),
					array(
						'type'		=> 'dropdown',
						'heading'	=> esc_html__('Enable Shadow', 'qode-tours'),
						'param_name'=> 'enable_shadow',
						'value'		=> array(
							esc_html__('No', 'qode-tours')  => 'no',
							esc_html__('Yes', 'qode-tours') => 'yes'
						)
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
						),
						'save_always' => true,
                        'dependency'  => array('element' => 'tour_type', 'value' => array('standard', 'gallery'))
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
                        'value'       => array_flip( bridge_qode_get_space_between_items_array() ),
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
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__('Enable Category Filter', 'qode-tours'),
						'param_name'  => 'filter',
						'value'       => array(
							esc_html__('No', 'qode-tours')  => 'no',
							esc_html__('Yes', 'qode-tours') => 'yes'
						),
						'save_always' => true,
						'description' => esc_html__('Default value is No', 'qode-tours'),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__('Enable Load More', 'qode-tours'),
						'param_name'  => 'enable_load_more',
						'value'       => array(
							esc_html__('No', 'qode-tours')  => 'no',
							esc_html__('Yes', 'qode-tours') => 'yes'
						),
						'save_always' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__('Load More Button Text', 'qode-tours'),
						'param_name'  => 'load_more_text',
						'dependency'  => array('element' => 'enable_load_more', 'value' => 'yes'),
						'description' => esc_html__('Default text is "Load More"', 'qode-tours')
					)
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
			'tour_item'                     => '3',
			'image_size'                    => 'full',
			'custom_image_dimensions'       => '',
			'space_between_items'			=> 'normal',
			'title_tag'	                    => 'h4',
			'text_length'                   => '90',
			'filter'                        => 'no',
			'enable_load_more'              => '',
			'load_more_text'                => '',
			'hover_color'					=> '',
			'enable_shadow'					=> 'no'
		);

		$args   = array_merge($args, qode_tours_query()->getShortcodeAtts());
		$params = shortcode_atts($args, $atts);
		
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

		return qode_tours_get_tour_module_template_part('tours-list/templates/tours-list-holder', 'tours', 'shortcodes', '', $params);
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