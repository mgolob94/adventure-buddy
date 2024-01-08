<?php

class QodeToursElementorTopReviewsCarousel extends \Elementor\Widget_Base{
	public function get_name() {
		return 'qode_top_reviews_carousel';
	}
	
	public function get_title() {
		return esc_html__( 'Top Reviews Carousel', 'qode-tours' );
	}
	
	public function get_icon() {
		return 'bridge-elementor-custom-icon bridge-elementor-top-reviews-carousel';
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
			'title',
			[
				'label' => esc_html__('Title', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::TEXT
			]
		);
		
		$this->add_control(
			'title_tag',
			[
				'label' => esc_html__('Title Tag', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => bridge_qode_get_title_tag( true ),
				'default' => 'h3'
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => esc_html__('Title Color', 'qode-music'),
				'type' => \Elementor\Controls_Manager::COLOR
			]
		);
		
		$this->add_control(
			'number_of_reviews',
			[
				'label' => esc_html__('Number of Reviews', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__('Leave empty for all', 'qode-tours'),
			]
		);
		
		$this->add_control(
			'review_criteria',
			[
				'label' => esc_html__('Order by Review Criteria', 'qode-tours'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $this->qode_rating_criteria_for_elementor()
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
		
		$this->end_controls_section();
		
	}
	
	protected function render(){
		$params = $this->get_settings_for_display();
		
		extract($params);
		
		$html = '';
		
		$params['reviews']        = $this->getTopReviews( $params );
		$params['title_style']    = $this->getTitleStyle( $params );
		$params['this_shortcode'] = $this;
		
		$html .= qode_tours_get_tour_module_template_part( 'top-reviews-carousel/templates/top-reviews-carousel', 'tours', 'shortcodes', '', $params );
		echo bridge_qode_get_module_part($html);
	}
	
	public function getTopReviews( $params ) {
		$number = isset( $params['number_of_reviews'] ) ? $params['number_of_reviews'] : '';
		
		$args = array(
			'status' => 'approve',
			'number' => $number,
			'post_type'	=> 'tour-item'
		);
		
		if ( isset( $params['review_criteria'] ) && ! empty( $params['review_criteria'] ) ) {
			$meta_query = array();
			
			$meta_query[]       = array(
				'key'     => $params['review_criteria'],
				'compare' => 'EXISTS'
			);
			$args['meta_query'] = $meta_query;
			$args['orderby']    = 'meta_value';
		}
		
		$comments = get_comments( $args );
		
		return $comments;
	}

	public function qode_rating_criteria_for_elementor() {
		$criteria_array  = bridge_core_rating_criteria('tour-item');

		$formatted_array = array();
		foreach ( $criteria_array as $criteria ) {
			$formatted_array[ $criteria['key'] ] = $criteria['label'];
		}

		return $formatted_array;
	}
	
	public function generateItemParams( $params ) {
		$comment                     = $params['comment'];
		$new_comment                 = array();
		$new_comment['comment_id']   = $comment->comment_ID;
		$new_comment['post_link']    = get_the_permalink( $comment->comment_post_ID );
		$new_comment['post_title']   = get_the_title( $comment->comment_post_ID );
		$new_comment['comment_text'] = get_comment_text( $comment->comment_ID );
		$new_comment['auhtor_email'] = $comment->comment_author_email;
		
		if ( isset( $params['review_criteria'] ) && ! empty( $params['review_criteria'] ) ) {
			$new_comment['review_value'] = get_comment_meta( $comment->comment_ID, $params['review_criteria'], true );
		}
		
		return $new_comment;
	}
	
	private function getTitleStyle( $params ){
		$styles = array();
		
		if(!empty($params['title_color'])) {
			$styles[] = 'color: '.$params['title_color'];
		}
		
		return $styles;
	}
}

\Elementor\Plugin::instance()->widgets_manager->register( new QodeToursElementorTopReviewsCarousel() );