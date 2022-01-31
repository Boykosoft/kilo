<?php

namespace App\Services\Apple;

use App\Enums\SubscriptionStatus;
use App\Models\SubscriptionUpdateRequest;
use App\Models\UserSubscription;
use App\Services\PaymentProviders\PaymentProviderInterface;
use App\Services\SubscriptionManager;
use Exception;
use Throwable;

class ApplePaymentProvider implements PaymentProviderInterface
{
    protected const STATUS_INITIAL_BUY = 'INITIAL_BUY';
    protected const STATUS_DID_RENEW = 'DID_RENEW';
    protected const STATUS_DID_FAIL_TO_RENEW = 'DID_FAIL_TO_RENEW';
    protected const STATUS_CANCEL = 'CANCEL';

    /**
     * @param SubscriptionManager $subscriptionManager
     */
    public function __construct(protected SubscriptionManager $subscriptionManager) {}

    public function handleSubscriptionUpdate(SubscriptionUpdateRequest $request): bool
    {
        try {
            $newStatus = $this->mapStatus($request->setStatus);
        } catch (Throwable $exception) {
            // TODO notify exception
            return false;
        }


        switch ($request->setStatus) {
            case self::STATUS_INITIAL_BUY:
                $this->subscriptionManager->createSubscription(
                    $request->user,
                    $request->subscription,
                    (string) $request->providerTransactionId,
                    $request->setDate
                );
                break;
            case self::STATUS_CANCEL:
            case self::STATUS_DID_FAIL_TO_RENEW:
            case self::STATUS_DID_RENEW:
                $userSubscription = UserSubscription::whereUserId($request->user->id)
                    ->whereSubscriptionId($request->subscription->id)
                    ->first();
                if (!$userSubscription) {
                    return false;
                }
                $this->subscriptionManager->updateSubscriptionStatus(
                    $userSubscription,
                    $newStatus,
                    $request->setDate,
                    $request->providerTransactionId
                );
                break;
            default:
                return false;
        }

        return true;
    }

    /**
     * @param mixed $providerStatus
     * @return SubscriptionStatus
     * @throws Exception
     */
    public function mapStatus(mixed $providerStatus): SubscriptionStatus
    {
        switch ($providerStatus) {
            case self::STATUS_DID_RENEW:
            case self::STATUS_INITIAL_BUY:
                return SubscriptionStatus::ACTIVE;
            case self::STATUS_CANCEL:
                return SubscriptionStatus::CANCELLED;
            case self::STATUS_DID_FAIL_TO_RENEW:
                return SubscriptionStatus::EXPIRED;
            default:
                throw New Exception('Unknown status received');
        }
    }

    public function renew(UserSubscription $userSubscription): bool
    {
        // TODO: Implement renew() method.
        return true;
    }
}
