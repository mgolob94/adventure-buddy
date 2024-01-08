<?php
if(!function_exists('qode_tours_register_tour_categories_widget')) {
    /**
     * Function that register tour list widget
     */
    function qode_tours_register_tour_categories_widget($widgets) {
        register_widget( 'QodeTourCategoriesWidget' );
    }

    add_filter('widgets_init', 'qode_tours_register_tour_categories_widget');
}

