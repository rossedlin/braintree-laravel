<?php

namespace App\Http\Controllers;

use Braintree\Gateway;
use Braintree\Result\Successful;

class BraintreeController extends Controller
{
    public function complete(string $nonce)
    {
        $gateway = new Gateway([
            'environment' => config('braintree.environment'),
            'merchantId'  => config('braintree.merchantId'),
            'publicKey'   => config('braintree.publicKey'),
            'privateKey'  => config('braintree.privateKey'),
        ]);

        $result = $gateway->transaction()->sale([
            'amount'             => '10.00',
            'paymentMethodNonce' => $nonce,
            //'deviceData'         => $deviceDataFromTheClient,
            'options'            => [
                'submitForSettlement' => True,
            ],
        ]);

        if ($result instanceof Successful) {
            return response()->json(['success' => true]);
        }

        throw new \Exception('Failed');
    }
}
