<?php

namespace App\Http\Controllers\API\Seeker;

use App\Http\Controllers\Controller;
use App\Models\LeaderBooking;
use App\Models\Payment;
use App\Models\User;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Customer;
use Stripe\EphemeralKey;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Webhook;

class PaymentController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create Payment Intent for Leader Session Booking (SDK Ready)
     */
    public function checkoutSession(Request $request)
    {
        $request->validate([
            'leader_id' => 'required|exists:users,id',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
        ]);

        try {
            $user = $this->getOrCreateCustomer($request->user());
            $leader = User::findOrFail($request->leader_id);

            // Use leader's session price
            $amount = $leader->session_price > 0 ? $leader->session_price : 50.00;

            $starts_at = Carbon::parse($request->date.' '.$request->time);
            $ends_at = (clone $starts_at)->addHour();

            // 1. Create Leader Booking in Pending Status (Real Flow)
            $booking = LeaderBooking::create([
                'user_id' => $user->id,
                'leader_id' => $leader->id,
                'amount' => $amount,
                'status' => 'pending',
                'starts_at' => $starts_at,
                'ends_at' => $ends_at,
            ]);

            // 2. Create Ephemeral Key (Required for saved cards in Flutter SDK)
            $ephemeralKey = EphemeralKey::create(
                ['customer' => $user->stripe_customer_id],
                ['stripe_version' => '2022-11-15']
            );

            // 3. Create Payment Intent
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100, // Stripe expects amount in cents
                'currency' => 'usd',
                'customer' => $user->stripe_customer_id,
                'metadata' => [
                    'booking_id' => $booking->id,
                    'user_id' => $user->id,
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return $this->sendResponse([
                'paymentIntent' => $paymentIntent->client_secret,
                'ephemeralKey' => $ephemeralKey->secret,
                'customer' => $user->stripe_customer_id,
                'publishableKey' => config('services.stripe.key'),
                'booking_id' => $booking->id,
            ], 'Payment Gateway Ready');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], 500);
        }
    }

    /**
     * Helper to get or create Stripe Customer
     */
    private function getOrCreateCustomer($user)
    {
        if (! $user->stripe_customer_id) {
            $customer = Customer::create([
                'email' => $user->email,
                'name' => $user->name,
            ]);
            $user->stripe_customer_id = $customer->id;
            $user->save();
        }

        return $user;
    }

    /**
     * Handle Stripe Webhook
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->processSuccessfulPayment($paymentIntent);
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->processFailedPayment($paymentIntent);
                break;
            default:
                Log::info('Unhandled Stripe event type: '.$event->type);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Process Successful Payment
     */
    private function processSuccessfulPayment($paymentIntent)
    {
        $bookingId = $paymentIntent->metadata->booking_id;
        $userId = $paymentIntent->metadata->user_id;

        $booking = LeaderBooking::find($bookingId);
        if ($booking) {
            $booking->update(['status' => 'paid']);

            // Create Payment Record
            Payment::create([
                'user_id' => $userId,
                'leader_booking_id' => $booking->id,
                'amount' => $paymentIntent->amount / 100,
                'transaction_id' => $paymentIntent->id,
                'status' => 'success',
            ]);
        }
    }

    /**
     * Process Failed Payment
     */
    private function processFailedPayment($paymentIntent)
    {
        $bookingId = $paymentIntent->metadata->booking_id;
        $userId = $paymentIntent->metadata->user_id;

        $booking = LeaderBooking::find($bookingId);
        if ($booking) {
            $booking->update(['status' => 'cancelled']);

            // Create Payment Record
            Payment::create([
                'user_id' => $userId,
                'leader_booking_id' => $booking->id,
                'amount' => $paymentIntent->amount / 100,
                'transaction_id' => $paymentIntent->id,
                'status' => 'failed',
            ]);
        }
    }
}
