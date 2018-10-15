<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatefcmAPIserviceAPIRequest;
use App\Http\Requests\API\UpdatefcmAPIserviceAPIRequest;
use App\Models\fcmAPIservice;
use App\Repositories\fcmAPIserviceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use DB;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

/**
 * Class fcmAPIserviceController
 * @package App\Http\Controllers\API
 */

class fcmAPIserviceAPIController extends AppBaseController
{
    /** @var  fcmAPIserviceRepository */
    private $fcmAPIserviceRepository;

    public function __construct(fcmAPIserviceRepository $fcmAPIserviceRepo)
    {
        $this->fcmAPIserviceRepository = $fcmAPIserviceRepo;
    }


    /**
     * @param CreatefcmAPIserviceAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="fcm",
     *      summary="Add device token to a user",
     *      tags={"FCM service"},
     *      description="Add device token to a user",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="user_id",
     *          in="body",
     *          description="The user id to add his device token",
     *          required=true,
     *          @SWG\Schema(ref="#/definitions/fcmAPIservice")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="Token has been updated successfully",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/fcmAPIservice"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreatefcmAPIserviceAPIRequest $request)
    {
        $input = $request->all();
        $user_id = $input['user_id'];
        $token = $input['token'];

        if ( empty( $user_id ) )
            return $this->sendError('user_id is required.');

        if ( empty( $token ) )
            return $this->sendError('token is required.');

        $user = DB::table('users')->where('id', $user_id)->first();
        if ( !is_array($user->device_tokens) && empty( unserialize($user->device_tokens) ) ){
            $tokens = [];
            $tokens[]= $token;
        }else{
            $tokens = unserialize($user->device_tokens);
            if ( in_array( $token, $tokens ) ){
                return $this->sendResponse('', 'The token is already exist.');
            }
            $tokens[] = $token;
        }
        $tokens = array_unique($tokens);
        $tokens = array_filter($tokens);
        DB::table('users')
        ->where('id', $user_id)
        ->update(['device_tokens' => serialize( $tokens ) ]);

        return $this->sendResponse('', 'Token has been updated successfully.');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/fcmAPIservices/{id}",
     *      summary="Test fcmAPIservice",
     *      tags={"FCM service"},
     *      description="Test fcmAPIservice",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of fcmAPIservice",
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
     *                  ref="#/definitions/fcmAPIservice"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show( CreatefcmAPIserviceAPIRequest $request )
    {
        $input = $request->all();
        if ( empty( $input['user_id'] ) )
            return $this->sendError('user_id is required');

        $user = DB::table('users')->where('id', $input['user_id'])->first();
        if ( empty( $user ) )
            return $this->sendError('user doesn\'t exist ');

        if ( empty( $user->device_tokens ) )
            return $this->sendError('user doesn\'t has device token ');

        $optionBuiler = new OptionsBuilder();
        $optionBuiler->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder('my title');
        $notificationBuilder->setBody('Hello world')
                            ->setSound('default');
                            
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => 'my_data']);

        $option = $optionBuiler->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $token = $user->device_tokens;

        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

        //return Array - you must remove all this tokens in your database
        $downstreamResponse->tokensToDelete(); 

        //return Array (key : oldToken, value : new token - you must change the token in your database )
        $downstreamResponse->tokensToModify(); 

        //return Array - you should try to resend the message to the tokens in the array
        $downstreamResponse->tokensToRetry();

        // return Array (key:token, value:errror) - in production you should remove from your database the tokens
        return $this->sendResponse($downstreamResponse->numberSuccess(), 'Fcm sent successfully');
    }

    /**
     * @param int $id
     * @param UpdatefcmAPIserviceAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/fcmAPIservices/{id}",
     *      summary="Update the specified fcmAPIservice in storage",
     *      tags={"FCM service"},
     *      description="Update fcmAPIservice",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of fcmAPIservice",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="fcmAPIservice that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/fcmAPIservice")
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
     *                  ref="#/definitions/fcmAPIservice"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdatefcmAPIserviceAPIRequest $request)
    {
        $input = $request->all();


        /** @var fcmAPIservice $fcmAPIservice */
        $fcmAPIservice = $this->fcmAPIserviceRepository->findWithoutFail($id);

        if (empty($fcmAPIservice)) {
            return $this->sendError('Fcm A P Iservice not found');
        }

        $fcmAPIservice = $this->fcmAPIserviceRepository->update($input, $id);

        return $this->sendResponse($fcmAPIservice->toArray(), 'fcmAPIservice updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/fcmAPIservices/{id}",
     *      summary="Remove the specified fcmAPIservice from storage",
     *      tags={"FCM service"},
     *      description="Delete fcmAPIservice",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of fcmAPIservice",
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
    public function destroy($id)
    {
        /** @var fcmAPIservice $fcmAPIservice */
        $fcmAPIservice = $this->fcmAPIserviceRepository->findWithoutFail($id);

        if (empty($fcmAPIservice)) {
            return $this->sendError('Fcm A P Iservice not found');
        }

        $fcmAPIservice->delete();

        return $this->sendResponse($id, 'Fcm A P Iservice deleted successfully');
    }
}
