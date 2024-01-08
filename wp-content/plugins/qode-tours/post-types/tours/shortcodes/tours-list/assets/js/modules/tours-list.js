(function($) {
    'use strict';

    var toursListSC = {};
    if(typeof qode !== 'undefined'){
        qode.modules.toursListSC = toursListSC;
    }

    toursListSC.qodeOnWindowLoad = qodeOnWindowLoad;

    toursListSC.toursList = toursList;

    $(window).on('load', qodeOnWindowLoad);

    /*
     All functions to be called on $(window).load() should be in this function
     */
    function qodeOnWindowLoad() {
        toursList().init();
	    qodeInitElementorToursList();
    }

    function themeInstalled() {
        return typeof qode !== 'undefined';
    }

    function toursList() {
        var listItem = $('.qode-tours-list-holder'),
            listObject;

        var initList = function(listHolder) {
            listHolder.animate({opacity: 1});

            resizeTourItems(listHolder);

            listObject = listHolder.isotope({
                percentPosition: true,
                itemSelector: '.qode-tours-row-item',
                transitionDuration: '0.4s',
                isInitLayout: true,
                hiddenStyle: {
                    opacity: 0
                },
                visibleStyle: {
                    opacity: 1
                },
                masonry: {
                    columnWidth: '.qode-tours-list-grid-sizer'
                }
            });

            if(themeInstalled()) {
                initParallax();
            }

            $(window).resize(function() {
                resizeTourItems(listHolder);
            });

        };

        var initFilter = function(listFeed) {
            var filters = listFeed.find('.qode-tour-list-filter-item');

            filters.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var currentFilter = $(this);
                var type = currentFilter.data('type');

                filters.removeClass('qode-tour-list-current-filter');
                currentFilter.addClass('qode-tour-list-current-filter');

                type = typeof type === 'undefined' ? '*' : '.' + type;

                listFeed.find('.qode-tours-list-holder-inner').isotope({
                    filter: type
                });
            });
        };

        var resetFilter = function(listFeed) {
            var filters = listFeed.find('.qode-tour-list-filter-item');

            filters.removeClass('qode-tour-list-current-filter');
            filters.eq(0).addClass('qode-tour-list-current-filter');

            listFeed.find('.qode-tours-list-holder-inner').isotope({
                filter: '*'
            });
        };

        var initPagination = function(listObject) {
            var paginationDataHolder = listObject.find('.qode-tours-list-pagination-data');
            var itemsHolder = listObject.find('.qode-tours-list-holder-inner');

            var fetchData = function(callback) {
                var data = {
                    action: 'qode_tours_list_ajax_pagination',
                    fields: paginationDataHolder.find('input').serialize()
                };

                $.ajax({
                    url: qodeToursAjaxURL,
                    data: data,
                    dataType: 'json',
                    type: 'POST',
                    success: function(response) {
                        if(response.havePosts) {
                            paginationDataHolder.find('[name="next_page"]').val(response.nextPage);
                        }

                        if(themeInstalled()) {
                            initParallax();
                        }

                        callback.call(this, response);
                    }
                });
            };
            
            var loadMorePagination = function() {
                var loadMoreButton = listObject.find('.qode-tours-load-more-button');
                var paginationHolder = listObject.find('.qode-tours-pagination-holder');
                var loadingInProgress = false;

                if(loadMoreButton.length) {
                    loadMoreButton.on('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        var loadingLabel = loadMoreButton.data('loading-label');
                        var originalText = loadMoreButton.text();
                        
                        loadMoreButton.text(loadingLabel);
                        resetFilter(listObject);

                        if(!loadingInProgress) {
                            loadingInProgress = true;

                            fetchData(function(response) {
                                if(response.havePosts === true) {
                                    loadMoreButton.text(originalText);

                                    var responseHTML = $(response.html);

                                    itemsHolder.append(responseHTML);

                                    itemsHolder.waitForImages(function() {
                                        resizeTourItems(itemsHolder);
                                        itemsHolder.isotope('appended', responseHTML).isotope('reloadItems');
                                        qode.modules.tours.qodeToursGalleryAnimation();
                                    });
                                } else {
                                    loadMoreButton.remove();

                                    paginationHolder.html(response.message);
                                }

                                loadingInProgress = false;
                            });
                        }

                    });
                }
            };

            loadMorePagination();
        };

        var resizeTourItems = function resizeTourItems(container){
            var itemsMainHolder = container.parent();
            if(itemsMainHolder.hasClass('qode-tours-type-masonry')) {
                var padding = parseInt(container.find('.qode-tours-row-item').css('padding-left')),
                    defaultMasonryItem = container.find('.qode-size-default'),
                    largeWidthMasonryItem = container.find('.qode-size-large-width'),
                    largeHeightMasonryItem = container.find('.qode-size-large-height'),
                    largeWidthHeightMasonryItem = container.find('.qode-size-large-width-height'),
                    size = container.find('.qode-tours-list-grid-sizer').width();

                if (qode.windowWidth > 680) {
                    defaultMasonryItem.css('height', size - 2 * padding);
                    largeHeightMasonryItem.css('height', Math.round(2 * size) - 2 * padding);
                    largeWidthHeightMasonryItem.css('height', Math.round(2 * size) - 2 * padding);
                    largeWidthMasonryItem.css('height', size - 2 * padding);
                } else {
                    defaultMasonryItem.css('height', size);
                    largeHeightMasonryItem.css('height', size);
                    largeWidthHeightMasonryItem.css('height', size);
                    largeWidthMasonryItem.css('height', Math.round(size / 2));
                }
            }
        };

        return {
            init: function() {
                if(listItem.length && typeof $.fn.isotope !== 'undefined') {
                    listItem.each(function() {
                        initList($(this).find('.qode-tours-list-holder-inner'));
                        initFilter($(this));
                        initPagination($(this));
                    });
                }
            }
        }
    }
	
	//Elementor reinitialization
	function qodeInitElementorToursList(){
		$j(window).on('elementor/frontend/init', function () {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/qode_tours_list.default', function() {
				toursList().init();
			} );
		});
	}
    
    return toursListSC;
    
})(jQuery);
