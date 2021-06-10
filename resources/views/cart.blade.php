<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://js.braintreegateway.com/web/dropin/1.30.0/js/dropin.js"></script>
    <title>Document</title>
</head>
<body>
    <form id="pay_form" action="{{ route('cart.checkout')}}" method="POST">
        @csrf
        @method('POST')
        <label for="name">Nome</label>
        <input type="text" id="name" name="name">

        <label for="lastname">cognome</label>
        <input type="text" id="lastname" name="lastname">

        <label for="email">email</label>
        <input type="email" id="email" name="email">

        <label for="address">indirizzo</label>
        <input type="text" id="address" name="address">

        <label for="phone">phone</label>
        <input type="text" id="phone" name="phone">
        
        <div id="dropin-container"></div>
        <input id="nonce" name="payment_method_nonce" type="hidden" />
        <button type="submit" > Invia </button>
        
        
    </form>
    <script>
        var form = document.querySelector('#pay_form');
        var token = "{{ $token }}"

        braintree.dropin.create({
        authorization: token,
        selector: '#dropin-container'
        }, function (err, instance) {
        form.addEventListener('submit',function (event) {
                event.preventDefault();
                instance.requestPaymentMethod(function (err, payload) {
                    if (err) {
                        console.log('Request Payment Method Error', err);
                        return;
                    }
                    // Add the nonce to the form and submit
                    document.querySelector('#nonce').value = payload.nonce;
                    form.submit();
                });
            })
        });
    </script>
</body>
</html>