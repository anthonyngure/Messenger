<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ClientSubscription
 *
 * @property int $id
 * @property int|null $client_id
 * @property int|null $subscription_id
 * @property int $quantity
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientSubscription whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientSubscription whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientSubscription whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientSubscription whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ClientSubscription extends Model
{
    //
}
