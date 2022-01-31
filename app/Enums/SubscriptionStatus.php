<?php
namespace App\Enums;

enum SubscriptionStatus: string {
    case ACTIVE = 'active';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';
    case ON_RENEW = 'on_renew';
}
