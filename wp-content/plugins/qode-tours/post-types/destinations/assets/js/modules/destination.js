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
