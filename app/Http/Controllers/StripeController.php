<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BalanceController;
use App\Http\Requests\StoreBalanceRequest;

class StripeController extends Controller
{
  public function getSession($amount, $userId)
  {
    // return "Amount: " . $amount;
    $stripe = new StripeClient(env('STRIPE_API_SECRET_KEY'));

    $checkout = $stripe->checkout->sessions->create([
      'payment_method_types' => ['card'],
      'line_items' => [
        [
          'price_data' => [
            'currency' => 'usd',
            'unit_amount' => $amount * 100,
            'product_data' => [
              'name' => 'Top-up',
              'description' => 'Balance top-up',
            ],
          ],
          'quantity' => 1,
        ],
      ],
      'mode' => 'payment',
      'success_url' => url('/payments/success') . '?session_id={CHECKOUT_SESSION_ID}',
      'cancel_url' => url('/payments/cancel') . '?session_id={CHECKOUT_SESSION_ID}',
      'metadata' => [
        'user_id' => $userId,
      ],
    ]);
    return $checkout;
  }

  public function handleWebhook(Request $request)
  {
    $endpoint_secret = env('STRIPE_API_WEBHOOK_SECRET');
    $payload = $request->getContent();
    $sig_header = $request->header('Stripe-Signature');
    $event = null;

    try {
      $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
    } catch (\UnexpectedValueException $e) {
      
      return response('Invalid payload', 400);
    } catch (\Stripe\Exception\SignatureVerificationException $e) {

      return response('Invalid signature', 400);
    }

    Log::info($event->type);
    if ($event->type == 'checkout.session.completed') {
      $session = $event->data->object;
      $userId = $session->metadata->user_id;

      $request = new StoreBalanceRequest();
      $request->merge(['amount' => $session->amount_total / 100]);
      $request->merge(['user_id' => $userId]);
      $balanceController = new BalanceController();
      $balanceController->increaseBalance($request);
    }

    return response('Webhook handled', 200);
  }
}
