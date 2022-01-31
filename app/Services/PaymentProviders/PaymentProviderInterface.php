<?php

namespace App\Services\PaymentProviders;

use App\Enums\SubscriptionStatus;
use App\Models\SubscriptionUpdateRequest;
use App\Models\UserSubscription;

interface PaymentProviderInterface
{
    public function handleSubscriptionUpdate(SubscriptionUpdateRequest $request): bool; // bool on return is simplify, use object in case of complex response logic
    public function mapStatus(mixed $providerStatus): SubscriptionStatus;
    public function renew(UserSubscription $userSubscription): bool;
}
