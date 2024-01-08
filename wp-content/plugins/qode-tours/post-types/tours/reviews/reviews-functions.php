<?php

/*
* Function that overrides default comment form in Tours plugin
*/
if(!function_exists('qode_tours_load_comment_template')){

    function qode_tours_load_comment_template( $comment_template ) {
        global $post;
        if(isset($post) && $post->post_type === 'tour-item'){
            if ( !( is_singular() && ( have_comments() || 'open' == $post->comment_status ) ) ) {
                return;
            }
            return QODE_TOURS_ABS_PATH.'/post-types/tours/templates/single/comments.php';
        }
        else{
            return $comment_template;
        }

    }
    add_filter( 'comments_template', 'qode_tours_load_comment_template');
}

/*
 * Function for generating taxonomy array
 */
function qode_taxonomy_rating_array( $taxonomy_name ) {
	/*
	** Get the necessary data about user-defined review taxonomy
	*/
	global $wpdb;
	
	if ( bridge_qode_is_wpml_installed() ) {
		$lang               = ICL_LANGUAGE_CODE;
		$wpml_taxonomy_name = 'tax_' . $taxonomy_name;
		$sql                = "SELECT t.term_id AS 'id',
                       t.slug AS 'key', 
                       t.name AS 'label' 
				    FROM {$wpdb->prefix}terms t
				    LEFT JOIN {$wpdb->prefix}term_taxonomy tt ON tt.term_id = t.term_id
				    LEFT JOIN {$wpdb->prefix}icl_translations icl_t ON icl_t.element_id = t.term_id
				    WHERE icl_t.element_type = '$wpml_taxonomy_name'
				    AND icl_t.language_code='$lang'
				    ORDER BY name ASC";
	} else {
		$sql = "SELECT t.term_id AS 'id',
                       t.slug AS 'key', 
                       t.name AS 'label' 
                    FROM {$wpdb->prefix}terms t
                    LEFT JOIN {$wpdb->prefix}term_taxonomy tt ON tt.term_id = t.term_id
                    WHERE tt.taxonomy = '$taxonomy_name'
                    ORDER BY name ASC";
	}
	
	$review_criteria = $wpdb->get_results( $sql, 'ARRAY_A' );
	
	$final_criteria = array();
	
	if ( ! empty( $review_criteria ) ) {
		$taxonomy_name_meta = str_replace( '-', '_', $taxonomy_name );
		foreach ( $review_criteria as $review_criterion ) {
			$temp_criterion          = (array) $review_criterion;
			$term_meta               = get_term_meta( $temp_criterion['id'] );
			$temp_criterion['show']  = ( isset( $term_meta[ $taxonomy_name_meta . '_show' ][0] ) && $term_meta[ $taxonomy_name_meta . '_show' ][0] != 'no' ) ? true : false;
			$temp_criterion['order'] = isset( $term_meta[ $taxonomy_name_meta . '_order' ][0] ) ? (int) $term_meta[ $taxonomy_name_meta . '_order' ][0] : PHP_INT_MAX;
			
			$final_criteria[] = $temp_criterion;
		}
		
		for ( $i = 0; $i < count( $final_criteria ) - 1; $i ++ ) {
			for ( $j = $i + 1; $j < count( $final_criteria ); $j ++ ) {
				if ( $final_criteria[ $i ]['order'] > $final_criteria[ $j ]['order'] ) {
					$temp                 = $final_criteria[ $i ];
					$final_criteria[ $i ] = $final_criteria[ $j ];
					$final_criteria[ $j ] = $temp;
				}
			}
		}
	}

    return $final_criteria;
}

/*
 * Function for defining post types that can be reviewed
 */
if ( ! function_exists( 'qode_rating_criteria_for_vc' ) ) {
	function qode_rating_criteria_for_vc() {
		$criteria_array  = bridge_core_rating_criteria('tour-item');
		$formatted_array = array();
		foreach ( $criteria_array as $criteria ) {
			$formatted_array[ $criteria['label'] ] = $criteria['key'];
		}
		
		return $formatted_array;
	}
}



/*
 * Function that is triggered when comment is edited
 */
/*if ( ! function_exists( 'qode_extend_comment_edit_metafields' ) ) {
	function qode_extend_comment_edit_metafields( $comment_id ) {
		if ( ( ! isset( $_POST['extend_comment_update'] ) || ! wp_verify_nonce( $_POST['extend_comment_update'], 'extend_comment_update' ) ) ) {
			return;
		}
		
		if ( ( isset( $_POST['qode_comment_title'] ) ) && ( $_POST['qode_comment_title'] != '' ) ):
			$title = wp_filter_nohtml_kses( $_POST['qode_comment_title'] );
			update_comment_meta( $comment_id, 'qode_comment_title', $title );
		else :
			delete_comment_meta( $comment_id, 'qode_comment_title' );
		endif;
		$comment = get_comment( $comment_id );
		$rating_criteria = qode_rating_criteria(get_post_type($comment->comment_post_ID));
		foreach ( $rating_criteria as $criteria ) {
			if ( ( isset( $_POST[ $criteria['key'] ] ) ) && ( $_POST[ $criteria['key'] ] != '' ) ):
				$rating = wp_filter_nohtml_kses( $_POST[ $criteria['key'] ] );
				update_comment_meta( $comment_id, $criteria['key'], $rating );
			else :
				delete_comment_meta( $comment_id, $criteria['key'] );
			endif;
		}
	}
	
	add_action( 'edit_comment', 'qode_extend_comment_edit_metafields' );
}*/

/*
 * Function that is triggered when comment is saved
 */
/*if ( ! function_exists( 'qode_extend_comment_save_metafields' ) ) {
	function qode_extend_comment_save_metafields( $comment_id ) {

		if ( ( isset( $_POST['qode_comment_title'] ) ) && ( $_POST['qode_comment_title'] != '' ) ) {
			$title = wp_filter_nohtml_kses( $_POST['qode_comment_title'] );
			add_comment_meta( $comment_id, 'qode_comment_title', $title );
		}
		$comment = get_comment( $comment_id );
		$rating_criteria = qode_rating_criteria(get_post_type($comment->comment_post_ID));
		foreach ( $rating_criteria as $criteria ) {
			if ( ( isset( $_POST[ $criteria['key'] ] ) ) && ( $_POST[ $criteria['key'] ] != '' ) ) {
				$rating = wp_filter_nohtml_kses( $_POST[ $criteria['key'] ] );
				add_comment_meta( $comment_id, $criteria['key'], $rating );
			}
		}
	}
	
	add_action( 'comment_post', 'qode_extend_comment_save_metafields' );
}*/

/*
 * Function that is triggered before comment is saved
 */
/*if ( ! function_exists( 'qode_extend_comment_preprocess_metafields' ) ) {
	function qode_extend_comment_preprocess_metafields( $commentdata ) {
		$post_types = qode_rating_posts_types();
		
		if ( is_array( $post_types ) && count( $post_types ) > 0 ) {
			foreach ( $post_types as $post_type ) {
				if ( is_singular( $post_type ) ) {
					$rating_criteria = qode_rating_criteria($post_type);
					foreach ( $rating_criteria as $criteria ) {
						if ( ! isset( $_POST[ $criteria['key'] ] ) ) {
							wp_die( esc_html__( 'Error: You did not add a rating. Hit the Back button on your Web browser and resubmit your comment with a rating.', 'qode' ) );
							break;
						}
					}
				}
			}
		}
		
		return $commentdata;
	}
	
	add_filter( 'preprocess_comment', 'qode_extend_comment_preprocess_metafields' );
}*/

/*
 * Function that through theme filter renders required fields in comment form on single post
 */
if(!function_exists('qode_tours_comment_additional_fields')) {

    function qode_tours_comment_additional_fields() {

        if (is_singular('tour-item')) {
            $html = '<div class="qode-rating-form-title-holder">'; //Form title begin
            /*$html .= '<div class="qode-rating-form-title">';
            $html .= '<h5>' . esc_html__('Write a Review','qode-tours') . '</h5>';
            $html .= '</div>';*/
            //$html .= qode_tours_get_tour_module_template_part('title-field', 'tours/reviews/templates/front-input','','','');
            $html .= '<div class="qode-comment-form-rating">
                        <label>' . esc_html__('Rate Here', 'qode-tours') . '
                            <span class="required">*</span>
                        </label><span class="qode-comment-rating-box">';
            $rating_criteria = bridge_core_rating_criteria(get_post_type());
            foreach ($rating_criteria as $criteria) {
                $star_params = array();
                $star_params['label'] = $criteria['label'];
                $star_params['key'] = $criteria['key'];
                $html .= qode_tours_get_tour_module_template_part('stars-field', 'tours/reviews/templates/front-input', '', '', $star_params);
            }
            $html .= '<input type="hidden" name="qode_rating" id="qode-rating" value="3">';
            $html .= '</span></div>';
            $html .= '</div>'; //Form title end

            $html .= '<div class="qode-comment-input-title">';
            $html .= '<input id="title" name="qode_comment_title" class="qode-input-field" type="text" placeholder="' . esc_html__('Title of your Review', 'qode-tours') . '"/>';
            $html .= '</div>';

            print $html;
        }
    }

    add_action( 'comment_form_top', 'qode_tours_comment_additional_fields' );

}

/*
 * Function that through theme filter renders listed comments on single post and it's callback
 */
if ( ! function_exists( 'qode_override_comments_list_callback' ) ) {
	function qode_override_comments_list_callback( $args ) {
		$post_types = bridge_core_rating_posts_types();
		
		if ( is_array( $post_types ) && count( $post_types ) > 0 ) {
			foreach ( $post_types as $post_type ) {
				if ( is_singular( $post_type ) ) {
					$args['callback'] = 'qode_list_reviews';
				}
			}
		}
		
		return $args;
	}
	
	add_filter( 'bridge_qode_filter_comments_callback', 'qode_override_comments_list_callback' );
}

if ( ! function_exists( 'qode_tours_post_reviews_html' ) ) {

    function qode_tours_post_reviews_html($reviews, $post_id) {

	    if( ! is_array( $reviews ) ){
		    $reviews = array();
	    }

        $post = get_post($post_id);
        $html = '';

        if(count($reviews)){

            foreach ($reviews as $comment){

                $is_pingback_comment = $comment->comment_type == 'pingback';
                $is_author_comment  = $post->post_author == $comment->user_id;

                $comment_class = 'qode-comment clearfix';

                if($is_author_comment) {
                    $comment_class .= ' qode-post-author-comment';
                }

                if($is_pingback_comment) {
                    $comment_class .= ' qode-pingback-comment';
                }
                $review_rating = get_comment_meta( $comment->comment_ID, 'qode_rating', true );
                $review_rating_style  = 'width: '.esc_attr( (int)$review_rating * 20 ) . '%';
                $review_title = get_comment_meta( $comment->comment_ID, 'qode_comment_title', true );
                $rating_criteria = bridge_core_rating_criteria(get_post_type());

                $comment_params = array(
                    'comment'   => $comment,
                    'is_pingback_comment' => $is_pingback_comment,
                    'is_author_comment' => $is_author_comment,
                    'comment_class' => $comment_class,
                    'review_rating_style' => $review_rating_style,
                    'review_title' => $review_title,
                    'rating_criteria' => $rating_criteria
                );
                $html .= qode_tours_get_tour_module_template_part('item-holder', 'tours/reviews/templates/front-list', '', '', $comment_params);
            }
        }
        return $html;
    }
}

/*
 * Functions for getting review details for rendering above comments list
 */
if ( ! function_exists( 'qode_list_review_details' ) ) {
	function qode_list_review_details( $template = 'simple' ) {

		$title    = bridge_qode_options()->getOptionValue( 'reviews_section_title' );
		$subtitle = bridge_qode_options()->getOptionValue( 'reviews_section_subtitle' );

		$template_name = 'details-' . $template;

		$params                  = array();
		$params['title']         = $title;
		$params['subtitle']      = $subtitle;
		$params['rating_number'] = qode_post_number_of_ratings();
		$params['rating_label']  = qode_post_number_of_ratings() === 1 ? esc_html__( 'Review', 'qode-tours' ) : esc_html__( 'Reviews', 'qode-tours' );
		$params['post_ratings']  = qode_post_ratings();

		return qode_tours_get_tour_module_template_part( $template_name, 'tours/reviews/templates/front-list', '', '', $params );
	}
}

/*
 * Functions for getting approved comments and their values for displaying info
 */
if ( ! function_exists( 'qode_post_ratings' ) ) {
	function qode_post_ratings( $id = '' ) {
		$id            = ! empty( $id ) ? $id : get_the_ID();
		$comment_array = get_approved_comments( $id );
		
		$rating_criteria = bridge_core_rating_criteria('tour-item');
		foreach ( $rating_criteria as $key => $criteria ) {
			$marks = array(
				'5' => 0,
				'4' => 0,
				'3' => 0,
				'2' => 0,
				'1' => 0
			);
			
			$count = 0;
			foreach ( $comment_array as $comment ) {
				$rating = get_comment_meta( $comment->comment_ID, $criteria['key'], true );
				
				if ( $rating != '' && $rating != 0 ) {
					$marks[ $rating ] = $marks[ $rating ] + 1;
					$count ++;
				}
			}
			
			$criteria['marks'] = $marks;
			$criteria['count'] = $count;
			
			$rating_criteria[ $key ] = $criteria;
		}
		
		return $rating_criteria;
	}
}

/*
 * Calculation functions
 */
if ( ! function_exists( 'qode_post_number_of_ratings' ) ) {
	function qode_post_number_of_ratings( $id = '' ) {
		$id            = ! empty( $id ) ? $id : get_the_ID();
		$comment_array = get_approved_comments( $id );
		$count         = ! empty( $comment_array ) ? count( $comment_array ) : 0;
		
		return $count;
	}
}

if ( ! function_exists( 'qode_post_average_rating' ) ) {
	function qode_post_average_rating( $criteria ) {
		$sum     = 0;
		$ratings = $criteria['marks'];
		$count   = $criteria['count'];
		foreach ( $ratings as $rating => $value ) {
			$sum = $sum + $rating * $value;
		}
		
		$average = $count == 0 ? 0 : round( $sum / $count );
		
		return $average;
	}
}

if ( ! function_exists( 'qode_post_average_rating_per_criteria' ) ) {
	function qode_post_average_rating_per_criteria( $criteria ) {
		$average = qode_post_average_rating( $criteria );
		$average = $average / QODE_REVIEWS_MAX_RATING * 100;
		
		return $average;
	}
}

if ( ! function_exists( 'qode_get_total_average_rating' ) ) {
	function qode_get_total_average_rating( $criteria_array ) {
		$sum = 0;
		
		if ( is_array( $criteria_array ) && count( $criteria_array ) ) {
			foreach ( $criteria_array as $criteria ) {
				$sum += qode_post_average_rating( $criteria );
			}
			
			return $sum / count( $criteria_array );
		}
		
		return $sum;
	}
}

/*
 * Formatting functions
 */
if ( ! function_exists( 'qode_reviews_format_rating_output' ) ) {
	function qode_reviews_format_rating_output( $rating ) {
		return floor( $rating * QODE_REVIEWS_POINTS_SCALE ) . '.' . round( $rating * QODE_REVIEWS_POINTS_SCALE * 10 ) % 10;
	}
}

if ( ! function_exists( 'qode_reviews_get_description_list' ) ) {
	function qode_reviews_get_description_list() {
		return array(
			esc_html__( 'Poor', 'qode-tours' ),
			esc_html__( 'Good', 'qode-tours' ),
			esc_html__( 'Superb', 'qode-tours' )
		);
	}
}

if ( ! function_exists( 'qode_reviews_get_description_for_rating' ) ) {
	function qode_reviews_get_description_for_rating( $rating ) {
		if ( ! $rating ) {
			return '';
		}
		
		$terms = qode_reviews_get_description_list();
		$delta = QODE_REVIEWS_MAX_RATING / count( $terms );
		
		return $terms[ ceil( $rating / $delta ) - 1 ];
	}
}
