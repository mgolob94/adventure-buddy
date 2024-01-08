jQuery(document).ready(function ($) {

    $('#custom-trigger-button').on('click', function () {
        var data = JSON.parse(localStorage.getItem('checkboxState'));

        $.ajax({
            url: ajaxurl, // WordPress AJAX URL
            type: 'POST',
            data: {
                action: 'custom_trigger_action', // Action name to trigger the server-side function
                data: JSON.stringify(data)
            },
            success: function (response) {
                // Handle the response from the server (if needed)
                console.log('Function triggered successfully');
            },
        });
    });
});
