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
