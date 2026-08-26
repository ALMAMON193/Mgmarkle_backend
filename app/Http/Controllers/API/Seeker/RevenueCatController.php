<?php

namespace App\Http\Controllers\API\Seeker;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RevenueCatController extends Controller
{
    use ApiResponse;

    /**
     * Handle incoming webhook requests from RevenueCat.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        // ---------------------------------------------------------------------
        // Step 1: Verify Authorization Token
        // ---------------------------------------------------------------------
        $expectedSecret = config('services.revenuecat.webhook_secret');
        $incomingHeader = (string) $request->header('Authorization');

        if ($expectedSecret && ! hash_equals($expectedSecret, $incomingHeader)) {
            Log::warning('RevenueCat Webhook: Unauthorized access attempt.');
            return $this->sendError('Unauthorized', [], 401);
        }

        // ---------------------------------------------------------------------
        // Step 2: Validate Payload Structure
        // ---------------------------------------------------------------------
        $event = $request->input('event');
        $userId = $event['app_user_id'] ?? null;
        $eventType = $event['type'] ?? null;

        if (! $userId || ! $eventType) {
            Log::error('RevenueCat Webhook: Invalid payload received.', ['payload' => $request->all()]);
            return $this->sendError('Invalid payload', [], 400);
        }

        // ---------------------------------------------------------------------
        // Step 3: Resolve User Instance
        // ---------------------------------------------------------------------
        // Extracts digits from app_user_id (e.g., "user_12" -> 12)
        $numericUserId = preg_replace('/\D/', '', $userId);
        $user = $numericUserId ? User::find($numericUserId) : null;

        // Gracefully handle test events or non-existent database users
        if (! $user) {
            Log::info("RevenueCat Webhook: Acknowledged event '{$eventType}' for non-db user '{$userId}'");
            return $this->sendResponse([], 'Test/Anonymous event acknowledged');
        }

        // ---------------------------------------------------------------------
        // Step 4: Process Event & Update User Subscription Status and Credits
        // ---------------------------------------------------------------------
        $productId = $event['new_product_id'] ?? $event['product_id'] ?? null;

        switch ($eventType) {
            case 'INITIAL_PURCHASE':
            case 'RENEWAL':
            case 'PRODUCT_CHANGE':
            case 'NON_RENEWING_PURCHASE': // Consumable single session purchase
                $creditsGranted = $this->calculateCreditsToGrant($productId);

                $user->update([
                    'product_id'    => $productId,
                    'package'       => $this->parsePackageName($productId),
                    'is_subscribed' => $eventType !== 'NON_RENEWING_PURCHASE' ? true : $user->is_subscribed,
                ]);

                if ($creditsGranted > 0) {
                    $user->increment('available_credits', $creditsGranted);
                }

                Log::info("RevenueCat Webhook: Added {$creditsGranted} credit(s) to user #{$user->id} for product '{$productId}'");
                break;

            case 'CANCELLATION':
            case 'EXPIRATION':
                $user->update([
                    'product_id'    => null,
                    'package'       => null,
                    'is_subscribed' => false,
                ]);
                break;

            case 'TEST':
            case 'TRANSFER':
            default:
                Log::info("RevenueCat Webhook: Ignored event '{$eventType}' for user #{$user->id}");
                return $this->sendResponse([], "Event '{$eventType}' acknowledged");
        }

        Log::info("RevenueCat Webhook: Successfully updated user #{$user->id} for event '{$eventType}'");

        return $this->sendResponse([
            'available_credits' => (int) $user->fresh()->available_credits,
            'is_subscribed'     => (bool) $user->is_subscribed,
            'package'           => $user->package,
        ], 'Webhook processed successfully');
    }

    /**
     * Calculate session credits to grant based on product identifier.
     *
     * @param string|null $productId
     * @return int
     */
    private function calculateCreditsToGrant(?string $productId): int
    {
        if (! $productId) {
            return 0;
        }

        $lowerProduct = strtolower($productId);

        // Premium Plan ($100/mo) -> 2 credits per month
        if (Str::contains($lowerProduct, ['premium', '100m', '100'])) {
            return 2;
        }

        // Standard Plan ($50/mo) or Single Session ($50) -> 1 credit
        if (Str::contains($lowerProduct, ['standard', '50m', '50', 'single', 'session'])) {
            return 1;
        }

        // Basic Plan ($10/mo) -> 0 live 1-on-1 session credits
        if (Str::contains($lowerProduct, ['basic', '10m', '10'])) {
            return 0;
        }

        // Default fallback for any other subscription purchase
        return 1;
    }

    /**
     * Parse package duration/type based on product ID identifier.
     *
     * @param string|null $productId
     * @return string|null
     */
    private function parsePackageName(?string $productId): ?string
    {
        if (! $productId) {
            return null;
        }

        $lower = strtolower($productId);

        if (Str::contains($lower, ['basic', '10'])) {
            return 'basic';
        }
        if (Str::contains($lower, ['standard', '50m'])) {
            return 'standard';
        }
        if (Str::contains($lower, ['premium', '100m'])) {
            return 'premium';
        }
        if (Str::contains($lower, ['single', 'session'])) {
            return 'pay_per_session';
        }

        return 'custom';
    }

    /**
     * Get Subscription & Session Credit Status of Authenticated User
     *
     * @return JsonResponse
     */
    public function getCredits(): JsonResponse
    {
        $user = auth('api')->user();

        if (! $user) {
            return $this->sendError('User not found', [], 404);
        }

        return $this->sendResponse([
            'available_credits' => (int) ($user->available_credits ?? 0),
            'is_subscribed'     => (bool) $user->is_subscribed,
            'product_id'        => $user->product_id,
            'package'           => $user->package,
        ], 'User credits and subscription status fetched successfully');
    }

    /**
     * Purchase / Sync Single Session Credit for authenticated user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function purchaseSingleSession(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|string',
        ]);

        $user = $request->user();

        // Increment 1 session credit
        $user->increment('available_credits', 1);

        Log::info("Single Session Credit purchased by User #{$user->id} (product: {$request->product_id})");

        return $this->sendResponse([
            'available_credits' => (int) $user->fresh()->available_credits,
            'is_subscribed'     => (bool) $user->is_subscribed,
            'product_id'        => $request->product_id,
        ], '1 session credit added to your account successfully.');
    }

    /**
     * Legacy alias for fetching subscription status
     *
     * @return JsonResponse
     */
    public function isSubscribed(): JsonResponse
    {
        return $this->getCredits();
    }
}
