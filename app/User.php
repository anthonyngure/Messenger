<?php
	
	namespace App;
	
	use Illuminate\Foundation\Auth\User as Authenticatable;
	use Illuminate\Notifications\Notifiable;
	use Tymon\JWTAuth\Contracts\JWTSubject;
	
	
	/**
 * App\User
 *
 * @property int
 *                   $id
 * @property string|null
 *                   $name
 * @property string|null
 *                   $email
 * @property string|null
 *                   $email_verification_code
 * @property int
 *                   $email_verified
 * @property string|null
 *                   $phone
 * @property string|null
 *                   $phone_verification_code
 * @property int
 *                   $phone_verified
 * @property string|null
 *                   $password
 * @property string|null
 *                   $password_recovery_code
 * @property string|null
 *                   $facebook_id
 * @property string|null
 *                   $facebook_picture_url
 * @property string
 *                   $account_type user - those who just ask for transport, driver - those who do transportation
 * @property float|null
 *                   $latitude
 * @property float|null
 *                   $longitude
 * @property int
 *                   $available
 * @property string|null
 *                   $remember_token
 * @property \Carbon\Carbon|null
 *                   $created_at
 * @property \Carbon\Carbon|null
 *                   $updated_at
 * @property-read mixed
 *                        $avg_rating
 * @property-read mixed
 *                        $count_negative
 * @property-read mixed
 *                        $count_positive
 * @property-read mixed
 *                        $sum_rating
 * @property-read mixed
 *                        $rating_percent
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[]
 *                $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Ghanem\Rating\Models\Rating[]
 *                        $ratings
 * @property-read \App\DriverDetail
 *                        $vehicle
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAccountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerificationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFacebookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFacebookPictureUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePasswordRecoveryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhoneVerificationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhoneVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string
 *                   $status       status only applies to account_type DRIVER
 * @property-read \App\DriverDetail
 *                        $driverDetail
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereStatus($value)
 * @property int|null $driver_option_id applies to drivers account only
 * @property-read \App\DriverOption $driverOption
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDriverOptionId($value)
 * @property int|null $role_id
 * @property string|null $avatar
 * @property-read \TCG\Voyager\Models\Role|null $role
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRoleId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Delivery[] $deliveries
 * @property int|null $client_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereClientId($value)
 * @property-read \App\Client|null $client
 */
	class User extends \TCG\Voyager\Models\User implements JWTSubject
	{
		use Notifiable;
		
		protected $guarded = ['id', 'created_at', 'updated_at'];
		
		/**
		 * The attributes excluded from the model's JSON form.
		 *
		 * @var array
		 */
		protected $hidden = [
			'client_id',
			'password',
			'email_verified',
			'phone_verified',
			'password_recovery_code',
			'email_verification_code',
			'phone_verification_code',
			'remember_token',
		];
		
		/**
		 * Get the identifier that will be stored in the subject claim of the JWT.
		 *
		 * @return mixed
		 */
		public function getJWTIdentifier()
		{
			return $this->getKey();
		}
		
		/**
		 * Return a code value array, containing any custom claims to be added to the JWT.
		 *
		 * @return array
		 */
		public function getJWTCustomClaims()
		{
			$date = new \DateTime();
			
			return [
				'id'  => $this->getKey(),
				'iss',
				'iat' => $date->getTimestamp(),
				'exp',
				'nbf',
				'sub' => $this->getKey(),
				'jti',
			];
		}
		
		public function driverDetail()
		{
			return $this->hasOne(DriverDetail::class);
		}
		
		/**
		 * @return \Illuminate\Database\Eloquent\Relations\HasOne
		 */
		public function driverOption()
		{
			return $this->hasOne(DriverOption::class);
		}
		
		
		/**
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
		 */
		public function client()
		{
			return $this->belongsTo(Client::class);
		}
	}
