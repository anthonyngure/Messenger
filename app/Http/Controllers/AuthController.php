<?php
	/**
	 * Created by PhpStorm.
	 * User: Tosh
	 * Date: 17/01/2017
	 * Time: 20:10
	 */
	
	namespace App\Http\Controllers;
	

	use App\Exceptions\WrappedException;
	use App\Traits\SendsVerificationCode;
	use App\Traits\UserTrait;
	use App\User;
	use App\Utils;
	use Auth;
	use Hash;
	use Illuminate\Http\Request;
	use JWTAuth;
	
	class AuthController extends Controller
	{
		use SendsVerificationCode, UserTrait;
		
		/**
		 * @param \App\User  $user
		 * @param array|null $meta
		 * @return \Illuminate\Http\Response
		 */
		private function authenticateUser(User $user, array $meta = null)
		{
			$user = User::with('client')->findOrFail($user->getKey());
			$token = JWTAuth::fromUser($user);
			
			$user->token = $token;
			
			return $this->itemResponse($user, null, $meta == null ? array() : $meta)
				->header('Authorization', $token);
		}
		
		/**
		 * @param \Illuminate\Http\Request $request
		 * @return \Illuminate\Http\Response
		 * @throws \App\Exceptions\WrappedException
		 */
		public function facebookSignIn(Request $request)
		{
			
			$this->validate($request, [
				'email'              => 'required|email',
				'facebookId'         => 'required|numeric',
				'name'               => 'required',
				'facebookPictureUrl' => 'required',
			]);
			
			$user = User::whereFacebookId($request->facebookId)
				->orWhere('email', $request->email)->first();
			
			//Add user to the db if not found
			if (is_null($user)) {
				
				$newUser = User::create([
					'email'                => $request->email,
					'email_verified'       => true,
					'name'                 => $request->name,
					'facebook_id'          => $request->facebookId,
					'facebook_picture_url' => $request->pictureUrl,
				]);
				
				$user = $this->findUser($newUser->getKey());
			}
			
			//Return user details
			return $this->authenticateUser($user, ['message' => 'Signed in successfully!']);
		}


        /**
         * @param \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         * @throws WrappedException
         */
		public function signIn(Request $request)
		{
            $this->validate($request, [
                'signInId' => 'required',
                'password' => 'required',
            ]);

            $user = User::where('phone', Utils::normalizePhone($request->signInId))
                ->orWhere('email', $request->signInId)->first();

            if ($user == null) {
                $errorMessage = $request->signInId . ' is not a registered email address or phone number. '
                    . 'Please use your correct Sign In details or create an account.';
                throw new WrappedException($errorMessage);
            }

            return $this->authenticateUser($user, ['message' => 'Signed In successfully.']);
		}
		
		/**
		 * @param \Illuminate\Http\Request $request
		 * @return \Illuminate\Http\Response
		 * @throws \App\Exceptions\WrappedException
		 */
		public function emailSignIn(Request $request)
		{
			sleep(1);
			$this->validate($request, [
				'email'    => 'required|email',
				'password' => 'required',
			]);
			
			$user = User::whereEmail($request->email)->first();
			
			if ($user == null) {
				$errorMessage = $request->username . ' is not a registered username. '
					. 'Please use your correct Sign In details.';
				throw new WrappedException($errorMessage);
			}
			
			if (!Hash::check($request->password, $user->password)) {
				throw new WrappedException("Wrong password.");
			}
			
			return $this->authenticateUser($user, ['message' => 'Signed In successfully.']);
		}
		
		/**
		 * @param \Illuminate\Http\Request $request
		 * @return \Illuminate\Http\Response
		 * @throws \App\Exceptions\WrappedException
		 */
		public function verifyPhone(Request $request)
		{
			$this->validate($request, [
				'phone' => 'required|digits:10',
				'code'  => 'required|digits:4',
			]);
			
			$user = User::wherePhone(Utils::normalizePhone($request->phone))->first();
			
			//If the phone is not registered the it cannot be verified
			if (is_null($user)) {
				$message = 'The phone number ' . $request->phone . ' cannot be verified!';
				throw new WrappedException($message);
			}
			
			if ($user->phone_verification_code != $request->code) {
				$message = 'The verification code entered was wrong!';
				throw new WrappedException($message);
			}
			
			$user->phone_verified = true;
			$user->save();
			
			return $this->authenticateUser($user, ['message' => 'Signed successfully.']);
			
		}
		
		public function user()
		{
			$user = User::with('client')->findOrFail(Auth::user()->id);
			
			return $this->itemResponse($user);
		}
		
		public function refresh()
		{
			$user = User::with('client')->findOrFail(Auth::user()->id);
			
			return $this->itemResponse($user);
		}
		
		public function signOut()
		{
			Auth::logout();
			
			return $this->arrayResponse([]);
		}
	}