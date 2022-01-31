<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserSubscription
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UserSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSubscription query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property int $subscription_id
 * @property string $status
 * @property string|null $set_status
 * @property string $provider_operation_id
 * @property string|null $set_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSubscription whereProviderOperationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSubscription whereSetDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSubscription whereSetStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSubscription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSubscription whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSubscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSubscription whereUserId($value)
 */
class UserSubscription extends Model
{
    use HasFactory;

    protected $table = 'user_subscriptions';
}
