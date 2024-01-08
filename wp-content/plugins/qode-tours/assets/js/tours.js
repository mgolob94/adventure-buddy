(function($) {
    'use strict';

    var destinations = {};
    if(typeof qode !== 'undefined'){
        qode.modules.destinations = destinations;
    }
    
    destinations.qodeOnDocumentReady = qodeOnDocumentReady;
    destinations.qodeOnWindowLoad = qodeOnWindowLoad;
    destinations.qodeOnWindowResize = qodeOnWindowResize;
    destinations.qodeOnWindowScroll = qodeOnWindowScroll;

    $(document).ready(qodeOnDocumentReady);
    $(window).on('load', qodeOnWindowLoad);
    $(window).resize(qodeOnWindowResize);
    $(window).scroll(qodeOnWindowScroll);

    /*
     All functions to be called on $(document).ready() should be in this function
     */
    function qodeOnDocumentReady() {
	    destinationShortcodeSearchFilters().fieldsHelper.init();
    }

    /*
     All functions to be called on $(window).load() should be in this function
     */
    function qodeOnWindowLoad() {
    }

    /*
     All functions to be called on $(window).resize() should be in this function
     */
    function qodeOnWindowResize() {
    }

    /*
     All functions to be called on $(window).scroll() should be in this function
     */
    function qodeOnWindowScroll() {
    }

    function themeInstalled() {
        return typeof qode !== 'undefined';
    }
    
    function destinationShortcodeSearchFilters() {
        var $searchForms = $('.qode-tours-filter-holder.qode-tours-filter-horizontal form');
        
        var fieldsHelper = function() {
            
            var initDestinationSearch = function() {
                var destinations = typeof qodeToursSearchData !== 'undefined' ? qodeToursSearchData.destinations : [];
                
                var destinations = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.whitespace,
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    local: destinations
                });
                
                $searchForms.find('.qode-tours-destination-search').typeahead({
                        hint: true,
                        highlight: true,
                        minLength: 1
                    },
                    {
                        name: 'destinations',
                        source: destinations
                    });
            };
            
            return {
                init: function() {
                    initDestinationSearch();
                }
            }
        }();
        
        return {
            fieldsHelper: fieldsHelper
        }
    }
    
    return destinations;
})(jQuery);

(function($) {
    'use strict';

    var tours = {};
    if(typeof qode !== 'undefined'){
        qode.modules.tours = tours;
    }
    
    tours.qodeOnDocumentReady = qodeOnDocumentReady;
    tours.qodeOnWindowLoad = qodeOnWindowLoad;
    tours.qodeOnWindowResize = qodeOnWindowResize;
    tours.qodeOnWindowScroll = qodeOnWindowScroll;

    tours.qodeInitTourItemTabs = qodeInitTourItemTabs;
    tours.qodeTourTabsMapTrigger = qodeTourTabsMapTrigger;
    tours.searchTours = searchTours;

    tours.qodeToursGalleryAnimation = qodeToursGalleryAnimation;

    $(document).ready(qodeOnDocumentReady);
    $(window).on('load', qodeOnWindowLoad);
    $(window).resize(qodeOnWindowResize);
    $(window).scroll(qodeOnWindowScroll);

    /*
     All functions to be called on $(document).ready() should be in this function
     */
    function qodeOnDocumentReady() {

        if(typeof qode === 'undefined' || typeof qode === '' ){
            //if theme is not installed, generate single items manualy
           qodeInitTourItemTabs();
        }

	    qodeTrigerTourGalleryMasonry();

        searchTours().fieldsHelper.init();
        searchTours().handleSearch.init();

        qodeToursGalleryAnimation();
    }

    /*
     All functions to be called on $(window).load() should be in this function
     */
    function qodeOnWindowLoad() {

        if(typeof qode !== 'undefined' ){
            //if theme is installed, trigger google map loading on location tab on single pages
            qodeTourTabsMapTrigger();

        }

        qodeTrigerTourGalleryMasonry();
    }

    /*
     All functions to be called on $(window).resize() should be in this function
     */
    function qodeOnWindowResize() {
	    qodeTourGalleryMasonry();
    }

    /*
     All functions to be called on $(window).scroll() should be in this function
     */
    function qodeOnWindowScroll() {

    }

    function themeInstalled() {
        return typeof qode !== 'undefined';
    }
	
	function qodeTourGalleryMasonry(){
		var masonryList = $('.qode-tour-masonry-gallery-holder .qode-tour-masonry-gallery');
		
		if(masonryList.length){
			masonryList.each(function(){
				var thisMasonry = $(this),
					thisMasonrySize = thisMasonry.find('.qode-tour-grid-sizer').width();
				
				thisMasonry.waitForImages(function() {
					thisMasonry.isotope({
						layoutMode: 'packery',
						itemSelector: '.qode-tour-gallery-item',
						percentPosition: true,
						packery: {
							gutter: '.qode-tour-grid-gutter',
							columnWidth: '.qode-tour-grid-sizer'
						}
					});
					
					thisMasonry.css('opacity', '1');
				});
			});
		}
	}
	
	function qodeTrigerTourGalleryMasonry(){
		var holder = $('.qode-tour-item-single-holder');
		var tourNavItems = holder.find('.qode-tour-item-wrapper ul li a');
		tourNavItems.on('click', function(){
			var thisNavItem  = $(this);
			var thisNavItemId = thisNavItem.attr('href');
			
			if(thisNavItemId === '#tab-tour-item-gallery-id'){
				qodeTourGalleryMasonry();
			}
		});
	}

    function qodeInitTourItemTabs(){
        var holder = $('.qode-tour-item-single-holder');
        var tourNavItems = holder.find('.qode-tour-item-wrapper ul li a');
        var tourSectionsItems  = holder.find('.qode-tour-item-section');
        tourNavItems.first().addClass('qode-active-item');

        tourNavItems.on('click', function(){
            tourNavItems.removeClass('qode-active-item');

            var thisNavItem  = $(this);
            var thisNavItemId = thisNavItem.attr('href');
            thisNavItem.addClass('qode-active-item');

            if( tourSectionsItems.length ){
                tourSectionsItems.each(function(){

                    var thisSectionItem = $(this);
                    if(thisSectionItem.attr('id') === thisNavItemId){
                        thisSectionItem.show();
                        if(thisNavItemId === '#tab-tour-item-location-id'){
                              qodeToursReInitGoogleMap();
                        }
                    }else{
                        thisSectionItem.hide();
                    }
                });
            }
        });
    }
    
    function qodeTourTabsMapTrigger(){
        var holder = $('.qode-tour-item-single-holder');
        var tourNavItems = holder.find('.qode-tour-item-wrapper ul li a');
        tourNavItems.on('click', function(){
            var thisNavItem  = $(this);
            var thisNavItemId = thisNavItem.attr('href');

            if(thisNavItemId === '#tab-tour-item-location-id'){
                qodeToursReInitGoogleMap();
            }
        });
    }
    
    function qodeToursReInitGoogleMap(){

        if(typeof qode !== 'undefined'){
            showGoogleMap();
        }
    }

    function searchTours() {
        var $searchForms = $('.qode-tours-search-main-filters-holder form');
        
        var fieldsHelper = function() {
            var initRangeSlider = function() {
                var $rangeSliders = $searchForms.find('.qode-tours-range-input');
                var $priceRange = $searchForms.find('.qode-tours-price-range-field');
                var $minPrice = $searchForms.find('[name="min_price"]');
                var $maxPrice = $searchForms.find('[name="max_price"]');
                var minValue = $priceRange.data('min-price');
                var maxValue = $priceRange.data('max-price');
                var chosenMinValue = $priceRange.data('chosen-min-price');
                var chosenMaxValue = $priceRange.data('chosen-max-price');
                
                if($rangeSliders.length) {
                    $rangeSliders.each(function() {
                        var thisSlider = this,
                            thisSliderObject = $(this);
                        
                        if( ! thisSliderObject.hasClass('qode-initialized') ) {
	                        thisSliderObject.addClass('qode-initialized');
	                        var slider = noUiSlider.create(thisSlider, {
		                        start: [chosenMinValue, chosenMaxValue],
		                        connect: true,
		                        step: 1,
		                        range: {
			                        'min': [minValue],
			                        'max': [maxValue]
		                        },
		                        format: {
			                        to: function (value) {
				                        return Math.floor(value);
			                        },
			                        from: function (value) {
				                        return value;
			                        }
		                        }
	                        });
	
	                        slider.on('update', function (values) {
		                        var firstValue = values[0];
		                        var secondValue = values[1];
		                        var currencySymbol = $priceRange.data('currency-symbol');
		                        var currencySymbolPosition = $priceRange.data('currency-symbol-position');
		
		                        var firstPrice = currencySymbolPosition === 'left' ? currencySymbol + firstValue : firstValue + currencySymbol;
		                        var secondPrice = currencySymbolPosition === 'left' ? currencySymbol + secondValue : secondValue + currencySymbol;
		
		                        $priceRange.val(firstPrice + ' - ' + secondPrice);
		
		                        $minPrice.val(firstValue);
		                        $maxPrice.val(secondValue);
	                        });
                        }
                    });
                }
            };
            
            var initKeywordSearch = function() {
                if( ! $searchForms.hasClass('qode-initialized') ){
	                $searchForms.addClass('qode-initialized')
	                var tours = typeof qodeToursSearchData !== 'undefined' ? qodeToursSearchData.tours : [];
	
	                var tours = new Bloodhound({
		                datumTokenizer: Bloodhound.tokenizers.whitespace,
		                queryTokenizer: Bloodhound.tokenizers.whitespace,
		                local: tours
	                });
	
	                $searchForms.find('.qode-tours-keyword-search').typeahead({
			                hint: true,
			                highlight: true,
			                minLength: 1
		                },
		                {
			                name: 'tours',
			                source: tours
		                });
                }
                
            };
            
            var initDestinationSearch = function() {
                var destinations = typeof qodeToursSearchData !== 'undefined' ? qodeToursSearchData.destinations : [];
                
                var destinations = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.whitespace,
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    local: destinations
                });
                
                $searchForms.find('.qode-tours-destination-search').typeahead({
                        hint: true,
                        highlight: true,
                        minLength: 1
                    },
                    {
                        name: 'destinations',
                        source: destinations
                    });
            };
            
            var initSelectPlaceholder = function() {
                var $selects = $('.qode-tours-select-placeholder');
                
                var changeState = function($select) {
                    var selectVal = $select.val();
                    
                    if(selectVal === '') {
                        $select.addClass('qode-tours-select-default-option');
                    } else {
                        $select.removeClass('qode-tours-select-default-option');
                    }
                };
                
                if($selects.length) {
                    $selects.each(function() {
                        var $select = $(this);
                        
                        changeState($(this));
                        
                        $select.on('change', function() {
                            changeState($(this));
                        });
                    })
                }
            };
            
            return {
                init: function() {
                    initRangeSlider();
                    initKeywordSearch();
                    initDestinationSearch();
                    initSelectPlaceholder();
                }
            }
        }();
        
        var handleSearch = function() {
            var rewriteURL = function(queryString) {
                //init variables
                var currentPage = '';
                
                //does current url has query string
                if (location.href.match(/\?.*/) && document.referrer) {
                    //get clean current url
                    currentPage = location.href.replace(/\?.*/, '');
                }
                
                //rewrite url with current page and new url string
                window.history.replaceState({page: currentPage + '?' + queryString}, '', currentPage + '?' + queryString);
            };
            
            var sendRequest = function($form, changeLabel, resetPagination, animate) {
                var $submitButton = $form.find('input[type="submit"]');
                var $searchContent = $('.qode-tours-search-content');
                var $searchPageContent = $('.qode-tours-search-page-holder');
                var searchInProgress = false;
                
                changeLabel = typeof changeLabel !== 'undefined' ? changeLabel : true;
                resetPagination = typeof resetPagination !== 'undefined' ? resetPagination : true;
                animate = typeof animate !== 'undefined' ? animate : false;
                
                var searchingLabel = $submitButton.data('searching-label');
                var originalLabel = $submitButton.val();
                
                if(!searchInProgress) {
                    if(changeLabel) {
                        $submitButton.val(searchingLabel);
                    }
                    
                    if(resetPagination) {
                        $form.find('[name="page"]').val(1);
                    }
                    
                    searchInProgress = true;
                    $searchContent.addClass('qode-tours-searching');
                    
                    var data = {
                        action: 'tours_search_handle_form_submission'
                    };
                    
                    data.fields = $form.serialize();
                    
                    $.ajax({
                        type: 'GET',
                        url: qodeToursAjaxURL,
                        dataType: 'json',
                        data: data,
                        success: function(response) {
                            if(changeLabel) {
                                $submitButton.val(originalLabel);
                            }
                            
                            $searchContent.removeClass('qode-tours-searching');
                            searchInProgress = false;
                            
                            $searchContent.find('.qode-tours-row .qode-tours-row-inner-holder').html(response.html);
                            rewriteURL(response.url);
                            
                            $('.qode-tours-search-pagination').remove();
                            
                            $searchContent.append(response.paginationHTML);
                            
                            if(animate) {
                                $('html, body').animate({scrollTop: $searchPageContent.offset().top - 80}, 700);
                            }
                            qodeToursGalleryAnimation();
                        }
                    });
                }
            };
            
            var formHandler = function($form) {
                
                if($('body').hasClass('post-type-archive-tour-item')) {
                    $form.on('submit', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        sendRequest($form);
                    });
                }
            };
            
            var handleOrderBy = function($searchForms) {
                var $orderingItems = $('.qode-search-ordering-item');
                var $orderByField = $searchForms.find('[name="order_by"]');
                var $orderTypeField = $searchForms.find('[name="order_type"]');
                
                if($orderingItems.length) {
                    $orderingItems.on('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        var $thisItem = $(this);
                        
                        $orderingItems.removeClass('qode-search-ordering-item-active');
                        $thisItem.addClass('qode-search-ordering-item-active');
                        
                        var orderBy = $thisItem.data('order-by');
                        var orderType = $thisItem.data('order-type');
                        
                        if(typeof orderBy !== 'undefined' && typeof orderType !== 'undefined') {
                            $orderByField.val(orderBy);
                            $orderTypeField.val(orderType);
                        }
                        
                        sendRequest($searchForms, false, false);
                    });
                }
            };
            
            var handleViewType = function($searchForms) {
                var $viewTypeItems = $('.qode-tours-search-view-item');
                var $typeField = $searchForms.find('[name="view_type"]');
                
                if($viewTypeItems.length) {
                    $viewTypeItems.on('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        var $thisView = $(this);
                        
                        $viewTypeItems.removeClass('qode-tours-search-view-item-active');
                        $thisView.addClass('qode-tours-search-view-item-active');
                        
                        var viewType = $thisView.data('type');
                        
                        if(typeof viewType !== 'undefined') {
                            $typeField.val(viewType);
                        }
                        
                        sendRequest($searchForms, false, false);
                    });
                }
            };
            
            var handlePagination = function($searchForms) {
                var $paginationHolder = $('.qode-tours-search-pagination');
                var $pageField = $searchForms.find('[name="page"]');
                
                if($paginationHolder.length) {
                    $(document).on('click', '.qode-tours-search-pagination li', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        var $thisItem = $(this);
                        
                        var page = $thisItem.data('page');
                        
                        if(typeof page !== 'undefined') {
                            $pageField.val(page);
                        }
                        
                        sendRequest($searchForms, true, false, true);
                    });
                }
            }
            
            return {
                init: function() {
                    formHandler($searchForms);
                    handleOrderBy($searchForms);
                    handleViewType($searchForms);
                    handlePagination($searchForms);
                }
            }
        }();
        
        return {
            fieldsHelper: fieldsHelper,
            handleSearch: handleSearch
        }
    }

    /*
    * Tours Gallery animation
    */
    function qodeToursGalleryAnimation() {
        var toursGalleryItems = $('.qode-tours-gallery-item');

        if (toursGalleryItems.length) {
            toursGalleryItems.each(function(){
                var toursGalleryItem = $(this),
                    contentHolder = toursGalleryItem.find('.qode-tours-gallery-item-content-holder'),
                    excerpt = contentHolder.find('.qode-tours-gallery-item-excerpt'),
                    deltaY = Math.ceil(excerpt.height());

                contentHolder.css('transform','translate3d(0,'+deltaY+'px,0)');

                toursGalleryItem.mouseenter(function(){
                    contentHolder.css('transform','translate3d(0,0,0)');
                });

                toursGalleryItem.mouseleave(function(){
                    deltaY = Math.ceil(excerpt.height());
                    contentHolder.css('transform','translate3d(0,'+deltaY+'px,0)');
                });
            });
        }
    }
    
    return tours;
})(jQuery);

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

(function ($) {
	'use strict';
	
	var rating = {};
	qode.modules.rating = rating;

    rating.qodeOnDocumentReady = qodeOnDocumentReady;
	
	$(document).ready(qodeOnDocumentReady);
	
	/*
	 All functions to be called on $(document).ready() should be in this function
	 */
	function qodeOnDocumentReady() {
		qodeInitCommentRating();
        qodeInitNewCommentShowHide();
	}
	
	function qodeInitCommentRating() {
		var ratingHolder = $('.qode-comment-form-rating > .qode-comment-rating-box > .qode-comment-form-rating');

        var addActive = function (stars, ratingValue) {
            for (var i = 0; i < stars.length; i++) {
                var star = stars[i];
                if (i < ratingValue) {
                    $(star).addClass('active');
                } else {
                    $(star).removeClass('active');
                }
            }
        };

		ratingHolder.each(function() {
		    var thisHolder = $(this),
                ratingInput = thisHolder.find('.qode-rating'),
                ratingValue = ratingInput.val(),
                stars = thisHolder.find('.qode-star-rating');

                addActive(stars, ratingValue);

            stars.click(function () {
                ratingInput.val($(this).data('value')).trigger('change');
            });

            ratingInput.change(function () {
                ratingValue = ratingInput.val();
                addActive(stars, ratingValue);
            });
        });
	}

    function qodeInitNewCommentShowHide() {
        var articles = $('.qode-tour-item-single-holder');

        if (articles.length) {
            articles.each(function () {
                var article = $(this),
                    panelHolderTrigger = article.find('.qode-rating-form-trigger'),
                    panelHolder = article.find('.qode-comment-form .comment-respond');

                panelHolderTrigger.on('click', function () {
                    panelHolder.slideToggle('slow');
                });
            });
        }
    }
	
})(jQuery);