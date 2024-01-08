(function($) {
    'use strict';

    var elementorToursCarousel = {};
    qode.modules.elementorToursCarousel = elementorToursCarousel;

    elementorToursCarousel.qodeInitElementorToursCarousel = qodeInitElementorToursCarousel;


    elementorToursCarousel.qodeOnWindowLoad = qodeOnWindowLoad;

    $(window).on('load', qodeOnWindowLoad);

    /*
     ** All functions to be called on $(window).load() should be in this function
     */
    function qodeOnWindowLoad() {
        qodeInitElementorToursCarousel();
    }

    function qodeInitElementorToursCarousel(){
        $j(window).on('elementor/frontend/init', function () {
            elementorFrontend.hooks.addAction( 'frontend/element_ready/qode_tours_carousel.default', function() {
	            qodeOwlSlider();
            } );
        });
    }
    
})(jQuery);