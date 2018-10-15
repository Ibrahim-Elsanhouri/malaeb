<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller {
	/*
		    |--------------------------------------------------------------------------
		    | Register Controller
		    |--------------------------------------------------------------------------
		    |
		    | This controller handles the registration of new users as well as their
		    | validation and creation. By default this controller uses a trait to
		    | provide this functionality without requiring any additional code.
		    |
	*/

	use RegistersUsers;

	/**
	 * Where to redirect users after registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware('guest');
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data) {
		$validator = Validator::make($data, [
			'name'     => 'required|max:255|unique:users',
			'mobile'   => ['required', 'max:20', 'min:10', 'unique:users', 'regex:/^(009665|0096605|05|9665|\\+9665|05|5)?(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
			'password' => 'required|min:6|confirmed',
		]);
        $niceNames = array(
            'mobile' => 'رقم الجوال'
        );
        $validator->setAttributeNames($niceNames); 

        return $validator;
	}

	public function setAttributeNames(array $attributes) {
		$this->customAttributes = $attributes;

		return $this;
	}

	public function register(Request $request) {
		$confirmationCode = rand(1000, 9999);
		$request->merge(['confirmed' => 1234]);
		$this->validator($request->all())->validate();

		event(new Registered($user = $this->create($request->all())));
		// $smsSent = MobilySmsService::send( $request->mobile, $confirmationCode );
		// $this->guard()->login($user);
		// $user->confirmed = $confirmationCode
		$user->confirmed = 1234;
		$user->save();
		return redirect('/confirm?success=1&id=' . $user->id);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	protected function create(array $data) {
		return User::create([
			'name'      => $data['name'],
			'mobile'    => $data['mobile'],
			'confirmed' => $data['confirmed'],
			'password'  => bcrypt($data['password']),
		]);
	}

}
