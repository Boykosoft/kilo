<?php

namespace App\Models;

use DateTime;

class SubscriptionUpdateRequest
{
    public User $user;
    public Subscription $subscription;
    public mixed $setStatus;
    public mixed $providerTransactionId;
    public ?DateTime $setDate = null;
}
