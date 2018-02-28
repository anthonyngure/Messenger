<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Subscription
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\ClientSubscription $clientSubscription
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereUpdatedAt($value)
 */
class Subscription extends Model
{
    //
	
	public function clientSubscription()
	{
		return $this->hasOne(ClientSubscription::class);
	}
}
