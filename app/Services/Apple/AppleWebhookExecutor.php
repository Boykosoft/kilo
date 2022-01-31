<?php

namespace App\Services\Apple;

use App\Models\Subscription;
use App\Models\SubscriptionUpdateRequest;
use App\Models\User;
use App\Services\PaymentProviders\WebhookExecutorInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppleWebhookExecutor implements WebhookExecutorInterface
{
    public function __construct(protected ApplePaymentProvider $paymentProvider) {}

    /**
     * @param Request $request
     * @return bool
     */
    public function handleHook(Request $request): bool
    {
        if (!$this->logRequest($request)) {
            // notify log error
        }
        if (!$this->validateRequest($request)
            || !$this->checkSignature($request)
        ) {
            return false;
        }
        if (!$subscriptionUpdateRequest = $this->extractData($request)) {
            return false;
        }

        return $this->paymentProvider->handleSubscriptionUpdate($subscriptionUpdateRequest);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function validateRequest(Request $request): bool
    {
        return $request->get('notification_type')
            && $request->get('signature')
            && $request->get('auto_renew_adam_id')
            && $request->get('auto_renew_product_id')
            && $request->get('original_transaction_id');
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function logRequest(Request $request): bool
    {
        // TODO log request
        return true;
    }

    /**
     * @param Request $request
     * @return SubscriptionUpdateRequest|null
     */
    public function extractData(Request $request): ?SubscriptionUpdateRequest
    {
        $subscriptionRenewRequest = new SubscriptionUpdateRequest;
        if (!$user = User::whereAppleId($request->get('auto_renew_adam_id'))->first()) {
            return null;
        }
        if (!$subscription = Subscription::whereName($request->get('auto_renew_product_id'))->first()) {
            return null;
        }

        $subscriptionRenewRequest->user = $user;
        $subscriptionRenewRequest->subscription = $subscription;
        $subscriptionRenewRequest->setStatus = $request->get('notification_type');
        $subscriptionRenewRequest->providerTransactionId = $request->get('original_transaction_id');
        if ($request->get('auto_renew_status_change_date_ms')) {
            $subscriptionRenewRequest->setDate = Carbon::createFromTimestampMs(
                $request->get('auto_renew_status_change_date_ms')
            );
        }

        return $subscriptionRenewRequest;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function postprocessHook(Request $request): bool
    {
        // No implementation for this provider
        return true;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function checkSignature(Request $request): bool
    {
        return $request->get('signature') === config('apple.password');
    }
}
