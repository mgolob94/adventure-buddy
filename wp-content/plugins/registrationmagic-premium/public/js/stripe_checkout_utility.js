(function ($) {
    $(document).ready(function () {

        $.ajax({
            url: rm_ajax.url,
            type: 'POST',
            data: {action: 'rm_stripe_localize_data', 'rm_sec_nonce': rm_admin_vars.nonce},
            async: true,
            success: function (result) {
                const stripe_keys = result;
                const stripe = Stripe(stripe_keys.public);
                const sub_id = $("form#rm-stripe-payment-form").data("submission-id");
                const log_id = $("form#rm-stripe-payment-form").data("log-id");
                const total_price = $("form#rm-stripe-payment-form").data("total-price");
                const description = $("form#rm-stripe-payment-form").data("description");
                const container = $('div.rm_stripe_fields');
                let elements;
        
                initialize(); checkStatus();
        
                document.querySelector("#rm-stripe-payment-form").addEventListener("submit", handleSubmit);
                
                function initialize() {
                    let data = {action: 'rm_get_intent_from_stripe', 'rm_sec_nonce': rm_admin_vars.nonce, sub_id: sub_id, total_price: total_price, description: description};
                    $.ajax({
                        url: rm_ajax.url,
                        type: 'POST',
                        data: data,
                        async: true,
                        success: function(response) {
                            const clientSecret = response.data.client_secret;
                            elements = stripe.elements({ clientSecret });
        
                            const paymentElement = elements.create("payment");
                            paymentElement.mount("#rm-stripe-payment-element");
                            if (jQuery(".rm-stripe-loader")[0]) {
                                document.querySelector(".rm-stripe-loader").remove();
                            }
                            
                        },
                        error: function(response) {
                            alert(response.data.msg);
                        }
                    });
                }
                
                async function handleSubmit(e) {
                    e.preventDefault();
                    setLoading(true);
                    
                    let rURL = window.location.href;
                    if(rURL.includes('?')) {
                        rURL = rURL + '&';
                    } else {
                        rURL = rURL + '?';
                    }
                    let returnURL = rURL+'total_price='+total_price+'&sub_id='+sub_id+'&log_id='+log_id+'&description='+description;
                    const { error } = await stripe.confirmPayment({
                        elements,
                        confirmParams: {
                            return_url: encodeURI(returnURL),
                            receipt_email: document.getElementById("rm-stripe-email").value,
                        },
                    });
                    
                    if (error.type === "card_error" || error.type === "validation_error") {
                        showMessage(error.message);
                    } else {
                        showMessage("An unexpected error occured.");
                    }
        
                    setLoading(false);
                }
                
                // Fetches the payment intent status after payment submission
                async function checkStatus() {
                    const clientSecret = new URLSearchParams(window.location.search).get(
                        "payment_intent_client_secret"
                    );
                    
                    if (!clientSecret) {
                        return;
                    }
        
                    const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);
        
                    switch (paymentIntent.status) {
                        case "succeeded":
                            showMessage("Payment succeeded!");
                            var data = {action: 'rm_stripe_after_intent', 'rm_sec_nonce': rm_admin_vars.nonce, intent_status: paymentIntent.status, intent: paymentIntent, total_price: total_price, sub_id: sub_id, current_url: get_current_url(), log_id: log_id, description: description};
                            $.ajax({
                                url: rm_ajax.url,
                                type: 'POST',
                                data: data,
                                async: true,
                                success: function (success_response) {
                                    container.html(success_response.data.msg);
                                    if (success_response.data.redirect) {
                                        location.href = success_response.data.redirect;
                                    }
                                    if (success_response.data.hasOwnProperty('reload_params')) {
                                        var url = [location.protocol, '//', location.host, location.pathname].join('');
                                        if(url.indexOf('admin-ajax.php')>=0){
                                            return;
                                        }
                                        url += success_response.data.reload_params;
                                        location.href = url;
                                    }
                                }
                            });
                            break;
                        case "processing":
                            showMessage("Your payment is processing.");
                            break;
                        case "requires_payment_method":
                            showMessage("Your payment was not successful, please try again.");
                            break;
                        default:
                            showMessage("Something went wrong.");
                            break;
                    }
                }
                
                function get_current_url() {
                    return location.protocol + '//' + location.host + location.pathname;
                }
        
                // ------- UI helpers -------
                
                function showMessage(messageText) {
                    const messageContainer = document.querySelector("#rm-stripe-payment-message");
        
                    messageContainer.classList.remove("rm-stripe-hidden");
                    messageContainer.textContent = messageText;
        
                    setTimeout(function () {
                        messageContainer.classList.add("rm-stripe-hidden");
                        messageText.textContent = "";
                    }, 4000);
                }
                
                function setLoading(isLoading) {
                    if (isLoading) {
                        document.querySelector("#rm-stripe-submit").disabled = true;
                        document.querySelector("#rm-stripe-spinner").classList.remove("rm-stripe-hidden");
                        document.querySelector("#rm-stripe-button-text").classList.add("rm-stripe-hidden");
                    } else {
                        document.querySelector("#rm-stripe-submit").disabled = false;
                        document.querySelector("#rm-stripe-spinner").classList.add("rm-stripe-hidden");
                        document.querySelector("#rm-stripe-button-text").classList.remove("rm-stripe-hidden");
                    }
                }
            },
            error: function (result) {
                alert('Unable to retrieve Stripe key. Please try again.');
                return;
            }
        });

    });
})(jQuery);