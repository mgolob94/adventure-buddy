<?php
if(!function_exists('qode_tours_register_tour_list_widget')) {
    /**
     * Function that register tour list widget
     */
    function qode_tours_register_tour_list_widget($widgets) {
        register_widget( 'QodeTourListWidget' );
    }

    add_filter('widgets_init', 'qode_tours_register_tour_list_widget');
}

