<?php

namespace App\Http\Controllers\API\Seeker;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Stripe\{Customer, EphemeralKey, PaymentMethod, SetupIntent, Stripe};

class PaymentCardController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create Setup Intent
     */
    public function createSetupIntent(Request $request)
    {
        try {
            $user = $this->getOrCreateCustomer($request->user());

            $ephemeralKey = EphemeralKey::create(
                ['customer' => $user->stripe_customer_id],
                ['stripe_version' => '2022-11-15']
            );

            $setupIntent = SetupIntent::create([
                'customer' => $user->stripe_customer_id,
                'payment_method_types' => ['card'],
            ]);

            return $this->sendResponse([
                'setupIntent' => $setupIntent->client_secret,
                'ephemeralKey' => $ephemeralKey->secret,
                'customer' => $user->stripe_customer_id,
                'publishableKey' => config('services.stripe.key'),
            ], 'Setup Intent Created Successfully');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], 500);
        }
    }

    /**
     * Add Manual Payment Method (with Duplicate Check)
     */
    public function addPaymentMethod(Request $request)
    {
        $request->validate(['payment_method_id' => 'required|string']);

        try {
            $user = $this->getOrCreateCustomer($request->user());
            
            $newMethod = PaymentMethod::retrieve($request->payment_method_id);
            $newFingerprint = $newMethod->card->fingerprint;

            // Check for duplicates
            $existingMethods = PaymentMethod::all([
                'customer' => $user->stripe_customer_id,
                'type' => 'card',
            ]);

            foreach ($existingMethods->data as $method) {
                if ($method->card->fingerprint === $newFingerprint) {
                     return $this->sendError('This card already exists.', [], 400);
                }
            }

            // If unique, attach
            $newMethod->attach(['customer' => $user->stripe_customer_id]);

            return $this->sendResponse([], 'Payment Method Added Successfully');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], 500);
        }
    }

    /**
     * List Payment Methods
     */
    public function getPaymentMethods(Request $request)
    {
        $user = $request->user();
        if (! $user->stripe_customer_id) {
            return $this->sendResponse([], 'No payment methods found');
        }

        try {
            $methods = PaymentMethod::all([
                'customer' => $user->stripe_customer_id,
                'type' => 'card',
            ]);

            $data = collect($methods->data)->map(function ($method) {
                return [
                    'id' => $method->id,
                    'brand' => $method->card->brand,
                    'last4' => $method->card->last4,
                    'exp_month' => $method->card->exp_month,
                    'exp_year' => $method->card->exp_year,
                ];
            });

            return $this->sendResponse($data, 'Payment Methods Retrieved Successfully');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], 500);
        }
    }

    /**
     * Remove Payment Method
     */
    public function detachPaymentMethod(Request $request)
    {
        $request->validate(['payment_method_id' => 'required|string']);

        try {
            PaymentMethod::retrieve($request->payment_method_id)->detach();

            return $this->sendResponse([], 'Payment Method Removed Successfully');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], 500);
        }
    }

    /**
     * Set Default Payment Method
     */
    public function setDefaultPaymentMethod(Request $request)
    {
        $request->validate(['payment_method_id' => 'required|string']);

        try {
            Customer::update($request->user()->stripe_customer_id, [
                'invoice_settings' => ['default_payment_method' => $request->payment_method_id],
            ]);

            return $this->sendResponse([], 'Default Payment Method Updated Successfully');

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
}
