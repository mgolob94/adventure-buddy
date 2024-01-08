(function($) {
    'use strict';

    var elementorToursFilter = {};
    qode.modules.elementorToursFilter = elementorToursFilter;

    elementorToursFilter.qodeInitElementorToursFilter = qodeInitElementorToursFilter;
    
    elementorToursFilter.qodeOnWindowLoad = qodeOnWindowLoad;

    $(window).on('load', qodeOnWindowLoad);

    /*
     ** All functions to be called on $(window).load() should be in this function
     */
    function qodeOnWindowLoad() {
        qodeInitElementorToursFilter();
    }

    function qodeInitElementorToursFilter(){
        $j(window).on('elementor/frontend/init', function () {
            elementorFrontend.hooks.addAction( 'frontend/element_ready/qode_tours_filter.default', function() {
	            qode.modules.tours.searchTours().fieldsHelper.init();
	            qode.modules.tours.searchTours().handleSearch.init();
	            // searchTours().fieldsHelper.init();
	            // searchTours().handleSearch.init();
            } );
        });
    }
    
})(jQuery);