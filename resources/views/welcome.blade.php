<!DOCTYPE html>
<html lang="en">
<head>
  <title>Braintree Laravel | Edlin App</title>
  <link rel="icon" href="https://assets.edlin.app/favicon/favicon.ico"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <script src="https://js.braintreegateway.com/web/3.100.0/js/client.min.js"></script>
  <script src="https://js.braintreegateway.com/web/3.100.0/js/hosted-fields.min.js"></script>
  {{--  <script src="path/to/bower_components/braintree-web/paypal.js"></script>--}}
  {{--  <script src="path/to/bower_components/braintree-web/data-collector.js"></script>--}}
  <link rel="stylesheet" href="/style.css">

</head>
<body>
<div class="demo-frame">
  <form action="/" method="post" id="cardForm">
    <label class="hosted-fields--label" for="card-number">Card Number</label>
    <div id="card-number" class="hosted-field"></div>

    <label class="hosted-fields--label" for="expiration-date">Expiration Date</label>
    <div id="expiration-date" class="hosted-field"></div>

    <label class="hosted-fields--label" for="cvv">CVV</label>
    <div id="cvv" class="hosted-field"></div>

    <label class="hosted-fields--label" for="postal-code">Postal Code</label>
    <div id="postal-code" class="hosted-field"></div>

    <div class="button-container">
      <input type="submit" class="button button--small button--green" value="Purchase" id="submit"/>
    </div>
  </form>
</div>
</body>
<script>
  let form = document.querySelector('#cardForm');
  let authorization = '{{config('braintree.tokenizationKey')}}';

  braintree.client.create({
    authorization: authorization
  }, function (err, clientInstance) {
    // if (err) {
    //   console.error(err);
    //   return;
    // }
    createHostedFields(clientInstance);
  });

  function createHostedFields(clientInstance) {
    braintree.hostedFields.create({
      client: clientInstance,
      styles: {
        'input': {
          'font-size': '16px',
          'font-family': 'courier, monospace',
          'font-weight': 'lighter',
          'color': '#ccc'
        },
        ':focus': {
          'color': 'black'
        },
        '.valid': {
          'color': '#8bdda8'
        }
      },
      fields: {
        number: {
          selector: '#card-number',
          placeholder: '4111 1111 1111 1111'
        },
        cvv: {
          selector: '#cvv',
          placeholder: '123'
        },
        expirationDate: {
          selector: '#expiration-date',
          placeholder: 'MM/YYYY'
        },
        postalCode: {
          selector: '#postal-code',
          placeholder: '11111'
        }
      }
    }, function (err, hostedFieldsInstance) {
      // if (err) {
      //   console.error(err);
      //   return;
      // }
      let tokenize = function (event) {
        event.preventDefault();

        hostedFieldsInstance.tokenize(function (err, payload) {
          // if (err) {
          //   alert('Something went wrong. Check your card details and try again.');
          //   return;
          // }

          fetch("/complete/" + payload.nonce, {method: "post", headers: {"X-CSRF-Token": '{{csrf_token()}}'}})
            .then((response) => {
              alert('Success!');
            })
            .catch((error) => {
              alert('Failed!');
            });
        });
      };

      form.addEventListener('submit', tokenize, false);
    });
  }
</script>
</html>
