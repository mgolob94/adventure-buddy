<?php

if (!function_exists('qode_tours_tours_category_fields')) {
	function qode_tours_tours_category_fields() {

		$tours_category_fields = bridge_qode_add_taxonomy_fields(
			array(
				'scope' => 'tour-category',
				'name'  => 'tour_category'
			)
		);

		bridge_qode_add_taxonomy_field(
			array(
				'name'        => 'tours_category_icon',
				'type'        => 'icon',
				'label'       => esc_html__( 'Choose Icon', 'qode-tours' ),
				'description' => esc_html__('Choose icon from icon pack for category.', 'qode-tours'),
				'parent'      => $tours_category_fields
			)
		);

		bridge_qode_add_taxonomy_field(
			array(
				'name'        => 'tours_category_custom_image',
				'type'        => 'image',
				'label'       => esc_html__( 'Custom Image', 'qode-tours' ),
				'description' => esc_html__('Choose custom image for category.', 'qode-tours'),
				'parent'      => $tours_category_fields
			)
		);
	}
	add_action('bridge_qode_action_custom_taxonomy_fields', 'qode_tours_tours_category_fields');
}

if (!function_exists('qode_tours_reviews_fields')) {
    function qode_tours_reviews_fields() {

        $tours_fields = bridge_qode_add_taxonomy_fields(
            array(
                'scope' => 'review-criteria',
                'name'  => 'review_criteria'
            )
        );

        bridge_qode_add_taxonomy_field(
            array(
                'name'        => 'review_criteria_order',
                'type'        => 'text',
                'label'       => esc_html__( 'Order', 'qode-tours' ),
                'description' => esc_html__('If there are multiple criteria, they will be displayed in an ascending order.', 'qode-tours'),
                'parent'      => $tours_fields
            )
        );

        bridge_qode_add_taxonomy_field(
            array(
                'name'        => 'review_criteria_show',
                'type'        => 'selectblank',
                'label'       => esc_html__( 'Show in Reviews', 'qode-tours' ),
                'description' => esc_html__('All the criteria can be rated when leaving a review, but only those marked to be shown will be displayed in the list of reviews.', 'qode-tours'),
                'options'	  => array(
                    'no' => esc_html__('No','qode-tours'),
                    'yes' => esc_html__('Yes','qode-tours'),
                ),
                'parent'      => $tours_fields
            )
        );
    }
    add_action('bridge_qode_action_custom_taxonomy_fields', 'qode_tours_reviews_fields');
}

add_filter("manage_edit-review-criteria_columns", 'qode_tour_review_criteria_columns');
function qode_tour_review_criteria_columns($columns) {
    $new_columns = array(
        'cb'                    => '<input type="checkbox" />',
        'name'                  => esc_html__('Name', 'qode-tours'),
        'slug'                  => esc_html__('Slug', 'qode-tours'),
        'review_criteria_order' => esc_html__('Order', 'qode-tours'),
        'review_criteria_show'  => esc_html__('Shown in Reviews', 'qode-tours'),
    );

    return $new_columns;
}

add_filter("manage_review-criteria_custom_column", 'qode_tour_review_criteria_column_values', 10, 3);
function qode_tour_review_criteria_column_values($out, $column_name, $term_id) {
    $term_meta = get_term_meta($term_id);
    switch($column_name) {
        case 'criteria_order':
            $out .= isset($term_meta['review_criteria_order'][0]) ? $term_meta['review_criteria_order'][0] : '-';
            break;
        case 'main_criterion':
            $out .= (isset($term_meta['review_criteria_show'][0]) && $term_meta['review_criteria_show'][0] == 'yes') ? esc_html__('Yes','qode-tours') : esc_html__('No','qode-tours');
            break;

        default:
            break;
    }

    return $out;
}

if(!function_exists('qode_tours_reviews_get_review_criteria')) {
    function qode_tours_reviews_get_review_criteria($default_criteria, $post_type) {

        if($post_type == 'tour-item') {
			$taxonomy_rating = qode_taxonomy_rating_array('review-criteria');
		} else {
			$taxonomy_rating = $default_criteria;
		}
        return $taxonomy_rating;
    }

    add_filter( 'bridge_core_rating_criteria', 'qode_tours_reviews_get_review_criteria', 10, 2 );
}