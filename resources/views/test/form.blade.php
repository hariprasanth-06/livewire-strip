<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <h2>Stripe Payment</h2>
    <button id="checkout-button">Pay with Stripe</button>
    <button id="refund-button">Refund with Stripe</button>

    <script>
        // Initialize Stripe
        const stripe = Stripe("{{ config('services.stripe.key') }}");

        $("#checkout-button").on("click", function() {
            $.ajax({
                url: "{{ route('payment.checkout') }}",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.id) {
                        stripe.redirectToCheckout({
                            sessionId: response.id
                        }).then(function(result) {
                            if (result.error) {
                                console.error("Stripe Error:", result.error.message);
                                alert("Payment Error: " + result.error.message);
                            }
                        });
                    } else {
                        console.error("Error: No session ID returned.");
                        alert("Error: Unable to process checkout.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                    alert("Error processing payment. Please try again.");
                }
            });
        });

        $("#refund-button").on("click", function() {
            $.ajax({
                url: "{{ route('payment.refund') }}",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.id) {
                        console.success("Error: No session ID returned.");
                        alert("Error: Unable to process checkout.");
                    } else {
                        console.error("Error: No session ID returned.");
                        alert("Error: Unable to process checkout.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                    alert("Error processing payment. Please try again.");
                }
            });
        });
    </script>
</body>

</html>
