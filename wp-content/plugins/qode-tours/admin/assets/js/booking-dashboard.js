(function ($) {
    'use strict';

    $(document).ready(qodeOnDocumentReady);

    /*
     All functions to be called on $(document).ready() should be in this function
     */
    function qodeOnDocumentReady() {
        qodeInitBookingDashboardActions();
    }

    /**
     * Initialize Booking Table Ajax Actions
     */
    function qodeInitBookingDashboardActions() {

        var actionButtons = $('.qode-booking-table-action-btn');

        if (actionButtons.length) {

            actionButtons.click(function (e) {
                e.preventDefault();
                var self = $(this),
                    bookingId = self.data('booking-id'),
                    action;

                if (self.hasClass('approve-booking')) {
                    action = 'approve';
                } else if (self.hasClass('cancel-booking')) {
                    action = 'cancel';
                }
                qodeChangeButtonStatus( bookingId, action );

            });

        }

    }

    function qodeChangeButtonStatus( id, action ) {

        var notice = $('.qode-booking-dash-notice');
        var ajaxData = {
            action: 'qodeToursChangeBookingStatus',
            bookingId: id,
            newStatus: action
        };

        $.ajax({
            type: 'POST',
            data: ajaxData,
            url: QodeToursAjaxUrl.url,
            success: function (data) {
                var response = JSON.parse( data );
                if ( response.status == 'success' ) {
                    notice.addClass(response.status);
                    notice.html(response.message);
                    notice.fadeIn(500);
                    window.location.reload();
                } else {
                    notice.addClass(response.status);
                    notice.html(response.message);
                    notice.fadeIn(500);
                    setTimeout(function(){
                        notice.fadeOut(500);
                    }, 1500);
                }
            }
        });


    }


})(jQuery);