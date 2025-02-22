<?php

namespace App\Http\Controllers;

use Stripe\Charge;
use Stripe\Stripe;
use App\Models\User;
use App\Models\Purchase;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;

class FormController extends Controller
{

    public $user;
    public function checkout(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'inr',
                    'product_data' => [
                        'name' => "Photo Purchase",
                        'description' => "Purchased Photo - ID: " . 1,
                    ],
                    'unit_amount' => 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'customer_creation' => 'always', // Ensures customer is created
            'billing_address_collection' => 'required', // Collects customer billing address
            'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}', // ✅ Correct way
            'cancel_url' => route('payment.cancel'),
            'metadata' => [
                'purchase_id' => 1,
                'user_id' => 1,
                'product_id' => 1,
            ],
        ]);

        return response()->json(['id' => $session->id]); // Return session ID
    }


    public function success(Request $request)
    {

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        if (!$request->has('session_id')) {
            return redirect()->route('payment.cancel')->with('error', 'Session ID missing.');
        }

        $session = \Stripe\Checkout\Session::retrieve($request->session_id);
        $paymentIntent = \Stripe\PaymentIntent::retrieve($session->payment_intent);

        // ✅ Check if payment was successful
        if ($paymentIntent->status === 'succeeded') {
            $purchase = Purchase::create([
                'user_id'           => 1,
                'purchase_type' => 0,
                'artist_id' => 1,
                'tokens' => 30,
                'product_id'        => 1,
                'transaction_id'    => $paymentIntent->id,
                'payment_method_id' => $paymentIntent->payment_method,
                'amount'            => $paymentIntent->amount,
                'currency'          => $paymentIntent->currency,
                'status'            => $paymentIntent->status,
                'latest_charge_id'  => $paymentIntent->latest_charge,
                'receipt_url'       => $paymentIntent->charges->data[0]['receipt_url'] ?? null,
            ]);

            return response()->json([
                'message' => 'Payment Successful!',
                'transaction_id' => $session->payment_intent
            ]);
        }

        return redirect()->route('payment.cancel')->with('error', 'Payment failed.');
    }

    public function cancel()
    {
        return "Payment canceled!";
    }

    public function Check()
    {
        $dataset = [
            'name' => 'hari',
            'email' => 'hari',
            'name' => 'hari',
        ];
        $users = User::where('Country', '!=', 'Mexico')->update($dataset)->first();

        return response()->json(
            [
                'status' => true,
                'data'  => $users,
                'message' => 'success',
            ],
            200
        );
    }
}
