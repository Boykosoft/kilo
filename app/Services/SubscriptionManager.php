<?php

namespace App\Services;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use DateTime;
use Illuminate\Support\Facades\DB;

class SubscriptionManager
{
    /**
     * @param UserSubscription $userSubscription
     * @param SubscriptionStatus $status
     * @param DateTime|null $setDate
     * @param mixed $providerTransactionId
     * @return bool
     */
    public function updateSubscriptionStatus(
        UserSubscription $userSubscription,
        SubscriptionStatus $status,
        ?DateTime $setDate, // date when subscription should be applied
        string $providerTransactionId
    ): bool {
        DB::transaction(function () use (
            &$userSubscription,
            $status,
            $setDate,
            $providerTransactionId
        ) {
            if (UserSubscription::whereProviderOperationId($providerTransactionId)->first()) {
                return false;
            }


            if ($setDate) {
                $userSubscription->set_status = $status;
                $userSubscription->set_date = $setDate;
            } else {
                $userSubscription->status = $status;
                $userSubscription->set_date = null;
                $userSubscription->set_status = null;
            }
            $userSubscription->provider_operation_id = $providerTransactionId;
            $userSubscription->save();

            return true;
        });

        return true;
    }

    /**
     * @param User $user
     * @param Subscription $subscription
     * @param string $providerTransactionId
     * @param DateTime|null $setDate
     * @return bool
     */
    public function createSubscription(
        User $user,
        Subscription $subscription,
        string $providerTransactionId,
        ?DateTime $setDate
    ): bool {
        DB::transaction(function () use (
            &$user,
            $subscription,
            $providerTransactionId,
            $setDate
        ) {
            if (UserSubscription::whereUserId($user->id)->first()) {
                return false;
            }

            $userSubscription = new UserSubscription;
            $userSubscription->user_id = $user->id;
            $userSubscription->subscription_id = $subscription->id;
            $userSubscription->status = SubscriptionStatus::ACTIVE;
            $userSubscription->provider_operation_id = $providerTransactionId;
            $userSubscription->set_status = SubscriptionStatus::EXPIRED;
            $userSubscription->set_date = $setDate;
            $userSubscription->save();
            return true;
        });

        return true;
    }
}
