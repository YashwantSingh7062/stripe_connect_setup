<script>
    var stripe = Stripe('<?= env('STRIPE_PUBLISHABLE_KEY', '') ?>');
    var elements = stripe.elements();
</script>
<div class="row mt-5">
    <div class="col-6 col-md-4 offset-md-4 offset-3">
        <div class="w-100 text-center">
            <h3 class="display-4">Yashwant Singh</h3>
            <p class="lead">Customer Panel</p>
        </div>
        <div class='card mt-5'>
            <div class='card-body text-center'>
                <form id="payment-form">
                    <div id="card-element" class="mb-4">
                        <!-- Elements will create input elements here -->
                    </div>

                    <!-- We'll put the error messages in this element -->
                    <div id="card-errors" role="alert"></div>

                    <button id="submit" class="mb-2">Pay</button>
                </form>
                Are you a service provider? <a href="<?= SITE_URL?>stripe/service_provider">Service Provider</a>
            </div>
        </div>  
    </div>
</div>



<script>
    var style = {
        base: {
            color: "#32325d",
        }
    };

    var card = elements.create("card", { style: style });
    card.mount("#card-element");

    card.on('change', ({error}) => {
        const displayError = document.getElementById('card-errors');
        if (error) {
            displayError.textContent = error.message;
        } else {
            displayError.textContent = '';
        }
    });

    var form = document.getElementById('payment-form');

    form.addEventListener('submit', function(ev) {
        ev.preventDefault();
        stripe.confirmCardPayment('<?= $client_secret ?>', {
            payment_method: {
            card: card,
                billing_details: {
                    name: 'My User',
                    email: 'newuser@mailinator.com'
                }
            }
        }).then(function(result) {
            if (result.error) {
            // Show error to your customer (e.g., insufficient funds)
            console.log(result.error.message);
            } else {
            // The payment has been processed!
                if (result.paymentIntent.status === 'succeeded') {
                    // Show a success message to your customer
                    // There's a risk of the customer closing the window before callback
                    // execution. Set up a webhook or plugin to listen for the
                    // payment_intent.succeeded event that handles any business critical
                    // post-payment actions.
                    alert("payment Done");
                    
                }
            }
        });
    });
</script>