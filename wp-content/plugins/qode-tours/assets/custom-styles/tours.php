<?php

if(!function_exists('qode_tours_first_color_styles') && qode_tours_theme_installed()) {
	function qode_tours_first_color_styles() {
		$first_color = bridge_qode_options()->getOptionValue('first_color');
		if(!empty($first_color)){

			$color_selector = ".qode-tour-item-single-holder article .qode-tour-item-price-holder .qode-tour-item-price .qode-tours-price-holder, .qode-tours-price-holder, .qode-tours-search-page-holder .qode-tours-search-pagination ul li a:hover, .qode-tours-search-page-holder .qode-tours-search-pagination ul li.active, .qode-tours-list-holder .qode-tour-list-filter-item.qode-tour-list-current-filter a, .qode-tours-list-holder .qode-tours-list-filter-holder ul li a:hover, .qode-tours-my-booking-item .qode-tours-info-items .qode-tours-booking-price, .qode-reviews-per-criteria .qode-item-reviews-average-rating, .qode-reviews-per-mark .qode-reviews-number, .qode-tours-checkout-content-inner .qode-tours-info-holder .qode-tours-info-message, .qode-tours-checkout-content-inner .qode-tours-info-holder .qode-tours-booking-price";
			$color_styles = array();


			$background_selectors = ".qode-tour-item-single-holder .qode-tabs.qode-horizontal .qode-tabs-nav li.ui-state-active a, .qode-tour-item-single-holder .qode-tabs.qode-horizontal .qode-tabs-nav li.ui-state-hover a, .qode-search-ordering-holder .qode-search-ordering-list li.qode-search-ordering-item-active a, .qode-search-ordering-holder .qode-search-ordering-list li:hover a, .qode-tour-item-label, .qode-tours-standard-item .qode-tours-standard-item-bottom-content, .qode-tours-search-main-filters-holder input[type=checkbox]:checked+label:before, .qode-tours-search-main-filters-holder .qode-tours-range-input .noUi-connect, .qode-tours-search-main-filters-holder .qode-tours-range-input .noUi-handle, .qode-tours-masonry-item.qode-tour-masonry-layout-default .qode-tours-gim-content-holder, #ui-datepicker-div .ui-datepicker-header, .qode-tour-item-single-holder .qode-tour-item-section .qode-route-id, .qode-tours-list-item .qode-tours-list-item-bottom-content, .tt-suggestion.tt-cursor, .tt-suggestion:hover, .qode-top-reviews-carousel-holder .qode-top-reviews-carousel.qode-owl-slider .owl-nav>div";
			$background_styles = array();


			$color_styles['color'] = $first_color;
			$background_styles['background-color'] = $first_color;


			echo bridge_qode_dynamic_css($color_selector, $color_styles);
			echo bridge_qode_dynamic_css($background_selectors, $background_styles);

		}
	}

	add_action('bridge_qode_action_style_dynamic', 'qode_tours_first_color_styles');
}