<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateusersAPIRequest;
use App\Http\Requests\API\UpdateusersAPIRequest;
use App\Library\MobilySmsService;
use App\Models\users;
use App\Repositories\usersRepository;
use DB;
use Hash;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class usersController
 * @package App\Http\Controllers\API
 */

class usersAPIController extends AppBaseController {
	/** @var  usersRepository */
	private $usersRepository;

	public function __construct(usersRepository $usersRepo) {
		$this->usersRepository = $usersRepo;
	}

	/**
	 * @param Request $request
	 * @return Response
	 *
	 * @SWG\Get(
	 *      path="/users",
	 *      summary="Get a listing of the users.",
	 *      tags={"users"},
	 *      description="Get all users",
	 *      produces={"application/json"},
	 *      @SWG\Response(
	 *          response=200,
	 *          description="successful operation",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(
	 *                  property="success",
	 *                  type="boolean"
	 *              ),
	 *              @SWG\Property(
	 *                  property="data",
	 *                  type="array",
	 *                  @SWG\Items(ref="#/definitions/users")
	 *              ),
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string"
	 *              )
	 *          )
	 *      )
	 * )
	 */
	public function index(Request $request) {
		$this->usersRepository->pushCriteria(new RequestCriteria($request));
		$this->usersRepository->pushCriteria(new LimitOffsetCriteria($request));
		$users = $this->usersRepository->all();

		return $this->sendResponse($users->toArray(), 'Users retrieved successfully');
	}

	/**
	 * @param CreateusersAPIRequest $request
	 * @return Response
	 *
	 * @SWG\Post(
	 *      path="/users",
	 *      summary="Store a newly created users in storage",
	 *      tags={"users"},
	 *      description="Store users",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="body",
	 *          in="body",
	 *          description="users that should be stored",
	 *          required=false,
	 *          @SWG\Schema(ref="#/definitions/users")
	 *      ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="successful operation",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(
	 *                  property="success",
	 *                  type="boolean"
	 *              ),
	 *              @SWG\Property(
	 *                  property="data",
	 *                  ref="#/definitions/users"
	 *              ),
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string"
	 *              )
	 *          )
	 *      )
	 * )
	 */
	public function store(CreateusersAPIRequest $request) {

		/**
		 * Replacing city field string with the desired id of city table
		 */
		if ( isset( $request['city'] ) ){
			$cities = DB::table('cities')->where('name_ar', 'LIKE', '%'.$request['city'].'%')->first();
			if ( $cities ){
				$request['city'] = $cities->id;
			}
		}
		$confirmationCode = isset($request->test) ? '1234' : rand(1000, 9999);
		$request->headers->set('Accept', 'application/json');
		$request->merge(['password' => \Hash::make($request->password)]);
		$request->merge(['confirmed' => $confirmationCode]);
		$request->merge(['confirmed' => $confirmationCode]);
		if ( $request->type == 'pg_owner' ){
			$request->merge(['id_cms_privileges' => 3]);
		}
		$input = $request->all();

		if (empty($request->mobile) && empty($request->name)) {
			$data = array(
				'success' => false,
				'data'    => array(
					'code' => 0,
				),
				'message' => 'the request can\'t be empty.',
			);
			return Response::json($data);

		}

		if (isset($request->mobile)) {
			$user = Users::where('mobile', '=', "$request->mobile")->first();
			if (!empty($user)) {
				$data = array(
					'success' => false,
					'data'    => array(
						'code' => 1
                        ),
					'message' => 'mobile number already exist',
				);
				return Response::json($data);
			}
		}

		if (isset($request->name)) {
			$user = Users::where('name', '=', "$request->name")->first();
			if (!empty($user)) {
				$data = array(
					'success' => false,
					'data'    => array(
						  'code' => 2
                        ),
    					'message' => 'Username already exist, please choose another one.',
    				);
    				return Response::json($data);
			}
		}

		try {
			if (!isset($request->test)) {
				$smsSent = MobilySmsService::send($request->mobile, $confirmationCode);
    				if (!$smsSent) {
                        $data = array(
                            'success' => false,
                            'data'    => array(
                                  'code' => 3
                                ),
                            'message' => 'Sorry some error occurred, please try again later.',
                        );
                        return Response::json($data);
				}

			}

			$users = $this->usersRepository->create($input);
			return $this->sendResponse($users->toArray(), 'User created successfully');

		} catch (Exception $e) {
			return $this->sendError('Can not create user');
		}

	}

	/**
	 * @param CreateusersAPIRequest $request
	 * @return Response
	 *
	 * @SWG\Post(
	 *      path="/users/login",
	 *      summary="login user",
	 *      tags={"users"},
	 *      description="you need to provide mobile||email||password",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="body",
	 *          in="body",
	 *          description="users that should be stored",
	 *          required=false,
	 *          @SWG\Schema(ref="#/definitions/users")
	 *      ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="successful operation",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(
	 *                  property="success",
	 *                  type="boolean"
	 *              ),
	 *              @SWG\Property(
	 *                  property="data",
	 *                  ref="#/definitions/users"
	 *              ),
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string"
	 *              )
	 *          )
	 *      )
	 * )
	 */
	public function login(CreateusersAPIRequest $request) {

        if ( empty($request->mobile) && empty($request->password)) {

            if (empty($request->mobile) && empty($request->name)) {
                $data = array(
                    'success' => false,
                    'data'    => array(
                        'code' => 0,
                    ),
                    'message' => 'the request can\'t be empty.',
                );
                return Response::json($data);

            }
        }

		if (isset($request->mobile) && !empty($request->mobile)) {
			$user = Users::where('mobile', 'LIKE', '%' . substr($request->mobile, -9))->first();
            if ( ! $user ){
                $data = array(
                    'success' => false,
                    'data'    => array(
                          'code' => 1
                        ),
                    'message' => 'User not found',
                );
                return Response::json($data);
            }
		}
        

        $user = Users::where('mobile', 'LIKE', '%' . substr($request->mobile, -9))->first();
        if(! Hash::check($request->password, $user->password)) {
            $data = array(
                    'success' => false,
                    'data'    => array(
                          'code' => 2
                        ),
                    'message' => 'Wrong password',
                );
            return Response::json($data);
        }
        
		if ($user->confirmed != 1) {
			$data = array(
				'success' => false,
				'data'    => array(
					'id'   => $user->id,
					'code' => 3,
					'name' => $user->name,
					'type' => $user->type,
				),
				'message' => 'User not confirmed',
			);
			return Response::json($data);
		}
		$user->last_login = date("Y-m-d H:i:s");
		$user->logged_out = 0;
		$user->save();
		$user->code = 1;

		/**
		 * Retrieve the language value of the city id ( for Cities DB table)
		 */
		if ( is_numeric( $user->city ) )
			$user->city = DB::table('cities')->select('name_ar')->where('id',$user->city )->first()->name_ar;

		return $this->sendResponse($user->toArray(), 'User logged successfully');
	}

	/**
	 * @param int $id
	 * @return Response
	 *
	 * @SWG\Get(
	 *      path="/users/{id}",
	 *      summary="Display the specified users",
	 *      tags={"users"},
	 *      description="Get users",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="id",
	 *          description="id of users",
	 *          type="integer",
	 *          required=true,
	 *          in="path"
	 *      ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="successful operation",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(
	 *                  property="success",
	 *                  type="boolean"
	 *              ),
	 *              @SWG\Property(
	 *                  property="data",
	 *                  ref="#/definitions/users"
	 *              ),
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string"
	 *              )
	 *          )
	 *      )
	 * )
	 */
	public function show($id) {
		/** @var users $users */
		$users = $this->usersRepository->findWithoutFail($id);

		if (empty($users)) {
            $data = array(
                    'success' => false,
                    'data'    => array(
                          'code' => 0
                        ),
                    'message' => 'User not found',
                );
            return Response::json($data);
		}
		/**
		 * Retrieve the language value of the city id ( for Cities DB table)
		 */
		if ( is_numeric( $users->city ) )
			$users->city = DB::table('cities')->select('name_ar')->where('id',$users->city )->first()->name_ar;

		return $this->sendResponse($users->toArray(), 'User retrieved successfully');
	}

	/**
	 * @param int $id
	 * @param UpdateusersAPIRequest $request
	 * @return Response
	 *
	 * @SWG\Put(
	 *      path="/users/{id}",
	 *      summary="Update the specified users in storage",
	 *      tags={"users"},
	 *      description="Update users",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="id",
	 *          description="id of users",
	 *          type="integer",
	 *          required=true,
	 *          in="path"
	 *      ),
	 *      @SWG\Parameter(
	 *          name="body",
	 *          in="body",
	 *          description="users that should be updated",
	 *          required=false,
	 *          @SWG\Schema(ref="#/definitions/users")
	 *      ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="successful operation",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(
	 *                  property="success",
	 *                  type="boolean"
	 *              ),
	 *              @SWG\Property(
	 *                  property="data",
	 *                  ref="#/definitions/users"
	 *              ),
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string"
	 *              )
	 *          )
	 *      )
	 * )
	 */
	public function update($id, UpdateusersAPIRequest $request) {
		$request->merge(['password' => Hash::make($request->password)]);

		$input = $request->all();

		/** @var users $users */
		$users = $this->usersRepository->findWithoutFail($id);

		if (empty($users)) {
			return $this->sendError('Users not found');
		}

		$users = $this->usersRepository->update($input, $id);

		return $this->sendResponse($users->toArray(), 'users updated successfully');
	}

	// $model = User::where('votes', '>', 100)->firstOrFail();

	/**
	 * @param int $id
	 * @return Response
	 *
	 * @SWG\Delete(
	 *      path="/users/{id}",
	 *      summary="Remove the specified users from storage",
	 *      tags={"users"},
	 *      description="Delete users",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="id",
	 *          description="id of users",
	 *          type="integer",
	 *          required=true,
	 *          in="path"
	 *      ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="successful operation",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(
	 *                  property="success",
	 *                  type="boolean"
	 *              ),
	 *              @SWG\Property(
	 *                  property="data",
	 *                  type="string"
	 *              ),
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string"
	 *              )
	 *          )
	 *      )
	 * )
	 */
	public function destroy($id) {

		return $this->sendError('Not allowed.');
	}

	/**
	 * @param int $id
	 * @return Response
	 *
	 * @SWG\Post(
	 *      path="/users/confirm",
	 *      summary="Confirm user registration",
	 *      tags={"users"},
	 *      description="Confirm user registration",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="body",
	 *          in="body",
	 *          description="user id and confirmation code",
	 *          required=true,
	 *          @SWG\Schema(ref="#/definitions/confirm")
	 *      ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="successful operation",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(
	 *                  property="success",
	 *                  type="boolean"
	 *              ),
	 *              @SWG\Property(
	 *                  property="data",
	 *                  type="string"
	 *              ),
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string"
	 *              )
	 *          )
	 *      )
	 * )
	 */
	public function confirm(CreateusersAPIRequest $request) {
		if (!isset($request->user_id) && !isset($request->code)) {
			return $this->sendError('the request can\'t be empty.');
		}

		$user = DB::table('users')->where('id', $request->user_id)->first();
		if (empty($user)) {
			$data = array(
                    'success' => false,
                    'data'    => array(
                          'code' => 0
                        ),
                    'message' => 'User not found',
                );
            return Response::json($data);
		}

		if ($user->confirmed == 1) {
            $data = array(
                    'success' => false,
                    'data'    => array(
                          'code' => 1
                        ),
                    'message' => 'user already confirmed',
                );
            return Response::json($data);
		}

		if (($request->code == 0) || ($user->confirmed != $request->code)) {
            $data = array(
                    'success' => false,
                    'data'    => array(
                          'code' => 2
                        ),
                    'message' => 'wrong confirmation code',
                );
            return Response::json($data);
		}

		try {
			DB::table('users')
				->where('id', $request->user_id)
				->update(['confirmed' => 1]);
			return $this->sendResponse($id, 'User confirmed successfully');
		} catch (Exception $e) {
			return $this->sendError('Can not confirm the user');
		}
	}

	/**
	 * @param int $id
	 * @return Response
	 *
	 * @SWG\Post(
	 *      path="/users/logout",
	 *      summary="user logout",
	 *      tags={"users"},
	 *      description="user logout",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="body",
	 *          in="body",
	 *          description="user id ",
	 *          required=true,
	 *          @SWG\Schema(ref="#/definitions/logout")
	 *      ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="successful operation",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(
	 *                  property="success",
	 *                  type="boolean"
	 *              ),
	 *              @SWG\Property(
	 *                  property="data",
	 *                  type="string"
	 *              ),
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string"
	 *              )
	 *          )
	 *      )
	 * )
	 */
	public function logout(CreateusersAPIRequest $request) {
		if (!isset($request->user_id)) {
			return $this->sendError('the request can\'t be empty.');
		}

		if (!isset($request->token)) {
			return $this->sendError('the token can\'t be empty.');
		}

		$user = DB::table('users')->where('id', $request->user_id)->first();
		/**
		 * Retrieve the language value of the city id ( for Cities DB table)
		 */
		if ( is_numeric( $user->city ) )
			$user->city = DB::table('cities')->select('name_ar')->where('id',$user->city )->first()->name_ar;

		if (empty($user)) {
			$data = array(
                    'success' => false,
                    'data'    => array(
                          'code' => 0
                        ),
                    'message' => 'User not found',
                );
            return Response::json($data);
		}

		try {
			$tokens = @unserialize($user->device_tokens);
			if ($tokens === false && $user->device_tokens !== 'b:0;') {
				return $this->sendResponse($user, 'User logged out successfully');
			}else{
				$key = array_search( $request->token, $tokens );
				if ( $key == 0 || $key  ){
					unset( $tokens[$key] );
					DB::table('users')
					->where('id', $request->user_id)
					->update(['device_tokens' => serialize($tokens)]);
					return $this->sendResponse($user, 'User logged out successfully');
				}else{
					return $this->sendResponse($user, 'User already logged out.');
				}

			}			
			
		} catch (Exception $e) {
			return $this->sendError('Can not logged out the user');
		}
	}

	/**
	 * @param int $id
	 * @return Response
	 *
	 * @SWG\Post(
	 *      path="/users/resend-code",
	 *      summary="Resend confirmation code.",
	 *      tags={"users"},
	 *      description="Resend confirmation code.",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="body",
	 *          in="body",
	 *          description="user id and test ( test will removed in production",
	 *          required=true,
	 *          @SWG\Schema(ref="#/definitions/resend-code")
	 *      ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="successful operation",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(
	 *                  property="success",
	 *                  type="boolean"
	 *              ),
	 *              @SWG\Property(
	 *                  property="data",
	 *                  type="string"
	 *              ),
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string"
	 *              )
	 *          )
	 *      )
	 * )
	 */
	public function resendConfirmation(CreateusersAPIRequest $request) {
		if (!isset($request->user_id)) {
			return $this->sendError('the request can\'t be empty.');
		}

		$user = DB::table('users')->where('id', $request->user_id)->first();
		if (empty($user)) {
            $data = array(
                    'success' => false,
                    'data'    => array(
                          'code' => 0
                        ),
                    'message' => 'User not found',
                );
            return Response::json($data);
		}

		if ($user->confirmed == 1) {
            $data = array(
                    'success' => false,
                    'data'    => array(
                          'code' => 1
                        ),
                    'message' => 'User already confirmed.',
                );
            return Response::json($data);
		}
		$confirmationCode = isset($request->test) ? '1234' : rand(1000, 9999);

		try {
			if (!isset($request->test)) {
				$smsSent = MobilySmsService::send($user->mobile, $confirmationCode);
				if (!$smsSent) {
					return $this->sendError('Sorry some error occurred, please try again later.');
				}

			}

			DB::table('users')
				->where('id', $request->user_id)
				->update(['confirmed' => $confirmationCode]);

			return $this->sendResponse($user->id, 'Confirmation code sent successfully.');
		} catch (Exception $e) {
			return $this->sendError('Can not confirm the user');
		}
	}

	/**
	 * @param int $id
	 * @return Response
	 *
	 * @SWG\Post(
	 *      path="/users/forget-password",
	 *      summary="Resend confirmation code.",
	 *      tags={"users"},
	 *      description="Resend confirmation code.",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="body",
	 *          in="body",
	 *          description="valid user mobile number and test ( test will removed in production",
	 *          required=true,
	 *          @SWG\Schema(ref="#/definitions/forget-password")
	 *      ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="successful operation",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(
	 *                  property="success",
	 *                  type="boolean"
	 *              ),
	 *              @SWG\Property(
	 *                  property="data",
	 *                  type="string"
	 *              ),
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string"
	 *              )
	 *          )
	 *      )
	 * )
	 */
	public function forgetPassword(CreateusersAPIRequest $request) {

		if (!isset($request->mobile)) {
			return $this->sendError('the request can\'t be empty.');
		}

		$user = DB::table('users')->where('mobile', $request->mobile)->first();
		if (empty($user)) {
            $data = array(
                    'success' => false,
                    'data'    => array(
                          'code' => 0
                        ),
                    'message' => 'Mobile number not exist.',
                );
            return Response::json($data);
		}

		/**
		 * Deprecated ( Request from IOS developer )
		 */
		// if ($user->confirmed != 1) {
  //           $data = array(
  //                   'success' => false,
  //                   'data'    => array(
  //                         'code' => 1
  //                       ),
  //                   'message' => 'User not confirmed.',
  //               );
  //           return Response::json($data);
		// }
		$new_password = rand(10000000, 99999999);
		$password     = \Hash::make($new_password);

		try {

			if (isset($request->mobile)) {

				$smsSent = MobilySmsService::send($request->mobile, $new_password, 'Your new password is:');

				if (!$smsSent) {
                    $data = array(
                            'success' => false,
                            'data'    => array(
                                  'code' => 2
                                ),
                            'message' => 'Sorry some error occurred, please try again later.',
                        );
                    return Response::json($data);
				}

				$return = $this->sendResponse($user->id, 'The message has been sent successfully to user with a new password');
			}

			DB::table('users')
				->where('mobile', $request->mobile)
				->update(['password' => $password]);

			return $return;

		} catch (Exception $e) {
			return $this->sendError('Can not set and send new password');
		}
	}

	/**
	 * @param int $id
	 * @return Response
	 *
	 * @SWG\Post(
	 *      path="/users/fb-login",
	 *      summary="Facebook login service.",
	 *      tags={"users"},
	 *      description="Facebook login service.",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="body",
	 *          in="body",
	 *          description="user email and mobile number",
	 *          required=true,
	 *          @SWG\Schema(ref="#/definitions/fb-login")
	 *      ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="successful operation",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(
	 *                  property="success",
	 *                  type="boolean"
	 *              ),
	 *              @SWG\Property(
	 *                  property="data",
	 *                  type="string"
	 *              ),
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string"
	 *              )
	 *          )
	 *      )
	 * )
	 */
	public function fbLogin(CreateusersAPIRequest $request) {

		if (!isset($request->fb_user_id)) {
			return $this->sendError('FB user id is required');
		}

		$user = DB::table('users')->where('fb_user_id', $request->fb_user_id)->first();

		if (empty($user) && !isset($request->mobile)) {
			$data = array(
				'success' => false,
				'data'    => array(
					'code' => 0,
				),
				'message' => 'New user, please send with valid mobile number',
			);
			return Response::json($data);
		}

		if (empty($user)) {

			if (empty($request->mobile)) {

                $data = array(
                    'success' => false,
                    'data'    => array(
                        'code' => 1,
                    ),
                    'message' => 'mobile number is required in order to create user.',
                );
                return Response::json($data);
			}

			$user = Users::where('mobile', '=', "$request->mobile")->first();

			if (!empty($user)) {
                $data = array(
                    'success' => false,
                    'data'    => array(
                        'code' => 2,
                    ),
                    'message' => 'Mobile number already exist, please try another one.',
                );
                return Response::json($data);
			}

			$request->headers->set('Accept', 'application/json');
			$request->merge(['password' => \Hash::make(rand(1000, 9999))]);
			$confirmationCode = isset($request->test) ? '1234' : rand(1000, 9999);
			$request->merge(['confirmed' => $confirmationCode]);
			$request->merge(['fb_user_id' => $request->fb_user_id]);
			$request->merge(['name' => $request->name]);

			if (isset($request->image)) {
				$request->image = "http://graph.facebook.com/$request->fb_user_id/picture?type=large";
				$image_code     = file_get_contents($request->image);
				$path           = public_path('uploads/users/');
				$img            = \Image::make($image_code);
				$extention      = explode('/', $img->mime());
				$filename       = time() . '.' . $extention[1];
				$img->save(public_path('uploads/users/' . $filename));
				$imgUrl = (asset('uploads/users/' . $filename));
				$request->merge(['image' => 'uploads/users/' . $filename]);
			}
			$input = $request->all();

			try {
				$users = $this->usersRepository->create($input);
				if ($users) {
					if (!isset($request->test)) {
						$smsSent = MobilySmsService::send($request->mobile, $confirmationCode);
						if (!$smsSent) {
                            $data = array(
                                'success' => false,
                                'data'    => array(
                                    'code' => 3,
                                ),
                                'message' => 'Sorry some error occurred, please try again later.',
                            );
                            return Response::json($data);
						}

					}
				}

                $responseData = $users->toArray();
                $responseData['code'] = 0;
                $data = array(
                    'success' => true,
                    'data'    => $responseData,
                    'message' => 'User created and confirmation code SMS sent.',
                );
                return Response::json($data);
			} catch (Exception $e) {
				return $this->sendError('Can not create user');
			}
		} else {
			$user = Users::where('fb_user_id', $request->fb_user_id)->first();
			if ($user->confirmed != '1') {
				$data = array(
					'success' => false,
					'data'    => array(
						'id'   => $user->id,
						'code' => 4,
						'name' => $user->name,
					),
					'message' => 'User not confirmed',
				);
				return Response::json($data);
			}
			$user->logged_out = 0;
			$user->save();
			$user->code = 1;
			/**
			 * Retrieve the language value of the city id ( for Cities DB table)
			 */
			if ( is_numeric( $user->city ) )
				$user->city = DB::table('cities')->select('name_ar')->where('id',$user->city )->first()->name_ar;
			return $this->sendResponse($user, 'User found and logged in successfully');
		}

	}

/**
 * @param int $id
 * @return Response
 *
 * @SWG\Post(
 *      path="/users/profile",
 *      summary="Update user profile.",
 *      tags={"users"},
 *      description="Update user profile.",
 *      produces={"application/json"},
 *      @SWG\Parameter(
 *          name="body",
 *          in="body",
 *          description="user id",
 *          required=true,
 *          @SWG\Schema(ref="#/definitions/profile")
 *      ),
 *      @SWG\Response(
 *          response=200,
 *          description="successful operation",
 *          @SWG\Schema(
 *              type="object",
 *              @SWG\Property(
 *                  property="success",
 *                  type="boolean"
 *              ),
 *              @SWG\Property(
 *                  property="data",
 *                  type="string"
 *              ),
 *              @SWG\Property(
 *                  property="message",
 *                  type="string"
 *              )
 *          )
 *      )
 * )
 */
	public function userProfile(UpdateusersAPIRequest $request) {

		/**
		 * Replacing city field string with the desired id of city table
		 */
		if ( isset( $request['city'] ) ){
			$cities = DB::table('cities')->where('name_ar', 'LIKE', '%'.$request['city'].'%')->first();
			if ( $cities ){
				$request['city'] = $cities->id;
			}
		}

		if (!isset($request->user_id)) {
			return $this->sendError('User id is required.');
		}

		if (isset($request->mobile)) {
			unset($request->mobile);
		}

		if (isset($request->password)) {
			unset($request->password);
		}

		$user = Users::where('id', '=', "$request->user_id")->first();
		if (!$user) {
            $data = array(
                    'success' => false,
                    'data'    => array(
                          'code' => 0
                        ),
                    'message' => 'User not found',
                );
            return Response::json($data);
		}

		if (isset($request->image)) {
			$path      = public_path('uploads/users/');
			$img       = \Image::make($request->image);
			$extention = explode('/', $img->mime());
			$filename  = time() . '.' . $extention[1];
			$img->save(public_path('uploads/users/' . $filename));
			$imgUrl = (asset('uploads/users/' . $filename));
			$request->merge(['image' => 'uploads/users/' . $filename]);
		}
		$input = $request->all();


		/** @var users $users */

		$users = $this->usersRepository->update($input, $request->user_id);

		$user = Users::where('id', '=', "$request->user_id")->first();
		/**
		 * Retrieve the language value of the city id ( for Cities DB table)
		 */
		if ( is_numeric( $user->city ) )
			$user->city = DB::table('cities')->select('name_ar')->where('id',$user->city )->first()->name_ar;

		return $this->sendResponse($user, 'user updated successfully');

	}

	/**
	 * @param int $id
	 * @return Response
	 *
	 * @SWG\Get(
	 *      path="/users/{id}/playgrounds/",
	 *      summary="Booked playground by user",
	 *      tags={"users"},
	 *      description="retrieve booked playground by user",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="body",
	 *          in="body",
	 *          description="user id",
	 *          required=true,
	 *          @SWG\Schema(ref="#/definitions/profile")
	 *      ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="successful operation",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(
	 *                  property="success",
	 *                  type="boolean"
	 *              ),
	 *              @SWG\Property(
	 *                  property="data",
	 *                  type="string"
	 *              ),
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string"
	 *              )
	 *          )
	 *      )
	 * )
	 */
	public function userPlaygrounds($user_id) {

		if (!isset($user_id)) {
			return $this->sendError('User id is required.');
		}

		$user = Users::where('id', '=', "$user_id")->first();
		if (!$user) {
            $data = array(
                    'success' => false,
                    'data'    => array(
                          'code' => 0
                        ),
                    'message' => 'User not found',
                );
            return Response::json($data);
        }
		$playgrounds                  = array();
		$playgrounds['not_confirmed'] = DB::table('reservations')
			->select('reservations.id', 'reservations.pg_id', 'reservations.time_id', 'reservations.created_at', 'pgtimes.from_datetime', 'playgrounds.pg_name', 'playgrounds.image', 'playgrounds.address')
			->where('reservations.player_id', $user_id)
			->where('reservations.confirmed', 0)
			->where('reservations.deleted_at', NULL)
			->join('pgtimes', 'pgtimes.id', '=', 'reservations.time_id')
			->join('playgrounds', 'playgrounds.id', '=', 'reservations.pg_id')
			->get()
			->toArray();
		foreach ($playgrounds['not_confirmed'] as $key => $value) {

			$datetime                                       = $playgrounds['not_confirmed'][$key]->from_datetime;
			$playgrounds['not_confirmed'][$key]->created_at = array('date' => date('Y-m-d', strtotime($datetime)), 'time' => date('g a', strtotime($datetime)));
			unset($playgrounds['not_confirmed'][$key]->from_datetime);
		}
		$playgrounds['confirmed'] = DB::table('reservations')
			->select('reservations.id', 'reservations.pg_id', 'reservations.time_id', 'reservations.created_at', 'pgtimes.from_datetime', 'playgrounds.pg_name', 'playgrounds.image', 'playgrounds.address')
			->where('reservations.player_id', $user_id)
			->where('reservations.confirmed', 1)
			->where('reservations.deleted_at', NULL)
			->join('pgtimes', 'pgtimes.id', '=', 'reservations.time_id')
			->join('playgrounds', 'playgrounds.id', '=', 'reservations.pg_id')
			->get()
			->toArray();

		foreach ($playgrounds['confirmed'] as $key => $value) {
			$datetime                                   = $playgrounds['confirmed'][$key]->from_datetime;
			$playgrounds['confirmed'][$key]->created_at = array('date' => date('Y-m-d', strtotime($datetime)), 'time' => date('h a', strtotime($datetime)));
			unset($playgrounds['confirmed'][$key]->from_datetime);
		}

		return $this->sendResponse($playgrounds, 'users updated successfully');

	}

	/**
	 * @param int $id
	 * @return Response
	 *
	 * @SWG\Post(
	 *      path="/users/change-password",
	 *      summary="Resend confirmation code.",
	 *      tags={"users"},
	 *      description="Resend confirmation code.",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="body",
	 *          in="body",
	 *          description="valid user mobile number and test ( test will removed in production",
	 *          required=true,
	 *          @SWG\Schema(ref="#/definitions/change-password")
	 *      ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="successful operation",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(
	 *                  property="success",
	 *                  type="boolean"
	 *              ),
	 *              @SWG\Property(
	 *                  property="data",
	 *                  type="string"
	 *              ),
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string"
	 *              )
	 *          )
	 *      )
	 * )
	 */
	public function changePassword(CreateusersAPIRequest $request) {

		if (!isset($request->old_password)) {
			return $this->sendError('Old password is empty.');
		}

		if (!isset($request->user_id)) {
			return $this->sendError('User ID is empty.');
		}
		$user = DB::table('users')->where('id', $request->user_id)->first();
        if (!$user) {
            $data = array(
                    'success' => false,
                    'data'    => array(
                          'code' => 0
                        ),
                    'message' => 'User not found',
                );
            return Response::json($data);
        }

		if (Hash::check($request->old_password, $user->password)) {
			if (!isset($request->new_password)) {
				return $this->sendError('new password is empty.');
			}

			$password = \Hash::make($request->new_password);
			DB::table('users')
				->where('id', $request->user_id)
				->update(['password' => $password]);

			return $this->sendResponse($user->id, 'Password has been updated successfully');
		} else {
			return $this->sendError('');
		}

	}

	/**
	 * @param int $id
	 * @return Response
	 *
	 * @SWG\Post(
	 *      path="/users/points",
	 *      summary="Update user points.",
	 *      tags={"users"},
	 *      description="Update user points.",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="body",
	 *          in="body",
	 *          description="user id",
	 *          required=true,
	 *          @SWG\Schema(ref="#/definitions/points")
	 *      ),
	 *      @SWG\Response(
	 *          response=200,
	 *          description="successful operation",
	 *          @SWG\Schema(
	 *              type="object",
	 *              @SWG\Property(
	 *                  property="success",
	 *                  type="boolean"
	 *              ),
	 *              @SWG\Property(
	 *                  property="data",
	 *                  type="string"
	 *              ),
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string"
	 *              )
	 *          )
	 *      )
	 * )
	 */
	public function userPoints(UpdateusersAPIRequest $request) {

		if (!isset($request->user_id)) {
			return $this->sendError('User id is required.');
		}

		if (isset($request->mobile)) {
			unset($request->mobile);
		}

		if (isset($request->password)) {
			unset($request->password);
		}

		if (isset($request->image)) {
			unset($request->image);
		}

		$user = Users::where('id', '=', "$request->user_id")->first();
		if (!$user) {
            $data = array(
                    'success' => false,
                    'data'    => array(
                          'code' => 0
                        ),
                    'message' => 'User not found',
                );
            return Response::json($data);
		}

		$input = $request->all();

		/** @var users $users */

		if ( isset($request->gift_id) ){
			$prize = DB::table('prizes')->where('id', $request->gift_id)->first();
			DB::table('user_prizes_history')->insert([
				    ['user_id' => $request->user_id,
				     'prize_id' => $request->gift_id]
			]);

		}

		$users = $this->usersRepository->update($input, $request->user_id);
		/**
		 * Retrieve the language value of the city id ( for Cities DB table)
		 */
		if ( is_numeric( $user->city ) )
			$user->city = DB::table('cities')->select('name_ar')->where('id',$user->city )->first()->name_ar;
		return $this->sendResponse($user, 'user points successfully');

	}
}

