(function($) {
    'use strict';

    var elementorTopReviewsCarousel = {};
    qode.modules.elementorTopReviewsCarousel = elementorTopReviewsCarousel;

    elementorTopReviewsCarousel.qodeInitElementorTopReviewsCarousel = qodeInitElementorTopReviewsCarousel;


    elementorTopReviewsCarousel.qodeOnWindowLoad = qodeOnWindowLoad;

    $(window).on('load', qodeOnWindowLoad);

    /*
     ** All functions to be called on $(window).load() should be in this function
     */
    function qodeOnWindowLoad() {
        qodeInitElementorTopReviewsCarousel();
    }

    function qodeInitElementorTopReviewsCarousel(){
        $j(window).on('elementor/frontend/init', function () {
            elementorFrontend.hooks.addAction( 'frontend/element_ready/qode_top_reviews_carousel.default', function() {
	            qodeOwlSlider();
            } );
        });
    }
    
})(jQuery);