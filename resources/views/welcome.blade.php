<!DOCTYPE html>
<html lang="en">
<head>
  <title>Braintree Laravel | Edlin App</title>
  <link rel="icon" href="https://assets.edlin.app/favicon/favicon.ico"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <script src="https://js.braintreegateway.com/web/dropin/1.42.0/js/dropin.js"></script>
  <link rel="stylesheet" href="/style.css">

</head>
<body>
<div id="dropin-container"></div>
<button id="submit-button">Purchase</button>
</body>
<script>
  let button = document.querySelector('#submit-button');

  braintree.dropin.create({
    authorization: '{{config('braintree.tokenizationKey')}}',
    selector: '#dropin-container'
  }, function (err, instance) {
    button.addEventListener('click', function () {
      instance.requestPaymentMethod(function (err, payload) {
        fetch("/complete/" + payload.nonce, {method: "post", headers: {"X-CSRF-Token": '{{csrf_token()}}'}})
          .then((response) => {
            if (response.status === 200) {
              alert('Success!');
            } else {
              alert('Failed!');
            }
          })
          .catch((error) => {
            alert('Failed!');
          });
      });
    })
  });
</script>
</html>
