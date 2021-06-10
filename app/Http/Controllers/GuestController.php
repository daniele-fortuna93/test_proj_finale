<?php

namespace App\Http\Controllers;

use App\Guest;
use App\Order;
use App\Payment;
use Braintree\Gateway;
use Illuminate\Http\Request;

class GuestController extends Controller
{

    public function cart()
    {

        $gateway = new Gateway([
            'environment' => config('services.braintree.environment'),
            'merchantId' => config('services.braintree.merchantId'),
            'publicKey' => config('services.braintree.publicKey'),
            'privateKey' => config('services.braintree.privateKey')
        ]);
        
        $token = $gateway->ClientToken()->generate();

        return view('cart', compact('token'));

    
        
    }

    public function checkout(Request $request)
    {
        

        $gateway = new Gateway([
            'environment' => config('services.braintree.environment'),
            'merchantId' => config('services.braintree.merchantId'),
            'publicKey' => config('services.braintree.publicKey'),
            'privateKey' => config('services.braintree.privateKey')
        ]);

        $order = Order::where('id', 1)->first();
        $guest_email = $request->email;
        $total = 150;
        $nonce = $request->payment_method_nonce;
        $guest = Guest::where('email', $guest_email)->first();
        
        if (  $guest == null ) {
            $guest = new Guest();
            $guest->name = $request->name;
            $guest->lastname = $request->lastname;
            $guest->address = $request->address;
            $guest->email = $request->email;
            $guest->phone = $request->phone;
            $guest->save();
        }

        $result = $gateway->transaction()->sale([
            'amount' => $total,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);
            
        if ( $result->success) {
            $transaction = $result->transaction;
            $newPayment = new Payment();
            $newPayment->order_id = $order->id;
            $newPayment->guest_id = $order->id;
            $newPayment->status = 'Success';
            $newPayment->save();

            return view('success');
        } else {
            return back()->withErrors('Qualcosa è andato storto');
        }


    }
    
}
