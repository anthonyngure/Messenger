<?php
	
	namespace App;
	
	use Illuminate\Database\Eloquent\Model;
	
	/**
 * App\DeliveryItem
 *
 * @property int                 $id
 * @property string              $origin_name
 * @property float               $origin_latitude
 * @property float               $origin_longitude
 * @property string              $destination_name
 * @property float               $destination_latitude
 * @property float               $destination_longitude
 * @property float               $estimated_cost
 * @property int                 $estimated_distance
 * @property int                 $estimated_duration
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereDestinationLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereDestinationLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereDestinationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereEstimatedCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereEstimatedDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereEstimatedDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereOriginLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereOriginLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereOriginName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int                 $courier_option_id
 * @property int                 $delivery_id
 * @property int                 $with_return
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereCourierOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereDeliveryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereWithReturn($value)
 * @property string $status
 * @property-read \App\CourierOption $courierOption
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereStatus($value)
 * @property string $destination_vicinity
 * @property string $destination_formatted_address
 * @property string|null $note
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereDestinationFormattedAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereDestinationVicinity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereNote($value)
 * @property string $contact
 * @property int $quantity
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereQuantity($value)
 * @property string $recipient_name
 * @property string $recipient_contact
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereRecipientContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DeliveryItem whereRecipientName($value)
 */
	class DeliveryItem extends Model
	{
		//
		
		protected $casts = [
			'with_return' => 'boolean',
		];
		protected $guarded = [
			'id', 'created_at', 'updated_at',
		];
		
		protected $hidden = [
			'courier_option_id', 'delivery_id', 'created_at', 'updated_at',
		];
		
		/**
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
		 */
		public function courierOption()
		{
			return $this->belongsTo(CourierOption::class);
		}
	}
