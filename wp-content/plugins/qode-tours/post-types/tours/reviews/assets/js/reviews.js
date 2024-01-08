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