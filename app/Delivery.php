<?php
	
	namespace App;
	
	use Illuminate\Database\Eloquent\Model;
	
	/**
 * App\Delivery
 *
 * @property int                                                               $id
 * @property int                                                               $user_id
 * @property string|null                                                       $date
 * @property string|null                                                       $time
 * @property string|null                                                       $note
 * @property \Carbon\Carbon|null                                               $created_at
 * @property \Carbon\Carbon|null                                               $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereUserId($value)
 * @mixin \Eloquent
 * @property string                                                            $schedule_date
 * @property string                                                            $schedule_time
 * @property string                                                            $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DeliveryItem[] $deliveryItems
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereScheduleDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereScheduleTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereStatus($value)
 * @property int $client_id
 * @property string $origin_name
 * @property string $origin_vicinity
 * @property string $origin_formatted_address
 * @property float $origin_latitude
 * @property float $origin_longitude
 * @property float $estimated_cost
 * @property float $estimated_max_distance
 * @property float $estimated_max_duration
 * @property float $actual_cost
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereActualCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereEstimatedCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereEstimatedMaxDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereEstimatedMaxDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereOriginFormattedAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereOriginLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereOriginLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereOriginName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Delivery whereOriginVicinity($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DeliveryItem[] $items
 */
	class Delivery extends Model
	{
		//
		
		protected $guarded = [
			'id', 'created_at', 'updated_at',
		];
		
		protected $hidden = [
			'client_id',
		];
		
		/**
		 * @return \Illuminate\Database\Eloquent\Relations\HasMany
		 */
		public function items()
		{
			return $this->hasMany(DeliveryItem::class);
		}
	}
