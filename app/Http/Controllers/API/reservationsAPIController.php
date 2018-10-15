<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreatereservationsAPIRequest;
use App\Http\Requests\API\UpdatereservationsAPIRequest;
use App\Models\pgtimes;
use App\Models\playgrounds;
use App\Models\reservations;
use App\Repositories\reservationsRepository;
use DB;
use FCM;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use Prettus\Repository\Criteria\RequestCriteria;
use Request;
use Response;

/**
 * Class reservationsController
 * @package App\Http\Controllers\API
 */

class reservationsAPIController extends AppBaseController {
	/** @var  reservationsRepository */
	private $reservationsRepository;

	public function __construct(reservationsRepository $reservationsRepo) {
		$this->reservationsRepository = $reservationsRepo;
	}

	/**
	 * @param Request $request
	 * @return Response
	 *
	 * @SWG\Get(
	 *      path="/reservations",
	 *      summary="Get a listing of the reservations.",
	 *      tags={"reservations"},
	 *      description="Get all reservations",
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
	 *                  @SWG\Items(ref="#/definitions/reservations")
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
		$this->reservationsRepository->pushCriteria(new RequestCriteria($request));
		$this->reservationsRepository->pushCriteria(new LimitOffsetCriteria($request));
		$reservations = $this->reservationsRepository->all();
		$reservations = reservations::with('playground')->with('time')->with('playground.user')->with('player')->get();
		return $this->sendResponse($reservations->toArray(), 'Reservations retrieved successfully');
	}

	/**
	 * @param CreatereservationsAPIRequest $request
	 * @return Response
	 *
	 * @SWG\Post(
	 *      path="/reservations",
	 *      summary="Store a newly created reservations in storage",
	 *      tags={"reservations"},
	 *      description="Store reservations",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="body",
	 *          in="body",
	 *          description="reservations that should be stored",
	 *          required=false,
	 *          @SWG\Schema(ref="#/definitions/reservations")
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
	 *                  ref="#/definitions/reservations"
	 *              ),
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string"
	 *              )
	 *          )
	 *      )
	 * )
	 */
	public function store(CreatereservationsAPIRequest $request) {
		$input        = $request->all();
		$time_ids     = explode(",", $input['time_id']);
		$pg_id        = $input['pg_id'];
		$reservations = array();
		foreach ($time_ids as $time_id) {
			$data            = $input;
			$data['time_id'] = $time_id;
			$reservations[]  = $this->reservationsRepository->create($data)->toArray();

			$pgtime = pgtimes::find($time_id);
			if (empty($pgtime)) {
				return $this->sendError('time not found');
			}
			$pgtime->first();
			$pgtime->booked = 1;
			$pgtime->save();
			$playground = playgrounds::find($pg_id);
			if (empty($playground)) {
				return $this->sendError('playground not found');
			}
			$playground->first();
			$playground->pg_BookingNumbers += 1;
			$playground->save();
			$onTime = date('Y-d-m h:m a', strtotime($pgtime->from_datetime));

			$arrEn   = array('am', 'pm');
			$arrAr   = array('ص', 'م');
			$fcmData = array(
				'title' => 'تمت عملية حجز الملعب بنجاح',
				'body'  => ' تم حجز ملعب "' . $playground->pg_name . '" بتاريخ ' . str_replace($arrEn, $arrAr, $onTime),
			);

			$optionBuiler = new OptionsBuilder();
			$optionBuiler->setTimeToLive(60 * 20);

			$notificationBuilder = new PayloadNotificationBuilder($fcmData['title']);
			$notificationBuilder->setBody($fcmData['body'])
				->setSound('default');

			$dataBuilder = new PayloadDataBuilder();
			if (isset($fcmData['data']) && !empty($data['data'])) {
				foreach ($fcmData['data'] as $key => $value) {
					$dataBuilder->addData([$key => $value]);
				}
			}

			$option       = $optionBuiler->build();
			$notification = $notificationBuilder->build();
			$data         = $dataBuilder->build();
			$tokens_array = DB::table('users')
				->select('device_tokens')
				->whereNotNull('device_tokens')
				->whereIn('id', [$playground->user_id])
				->where('logged_out', 0)
				->pluck('device_tokens')->toArray();
			$tokens = @unserialize($tokens_array[0]);
			if ($tokens === false && $tokens_array[0] !== 'b:0;' || empty($tokens)) {
				// return $this->sendResponse($reservations, 'Reservations saved successfully but notification not, please check device tokens');
			} else {
				$downstreamResponse = FCM::sendTo($tokens, $option, $notification);
			}
		}

		return $this->sendResponse($reservations, 'Reservations saved successfully');
	}

	/**
	 * @param int $id
	 * @return Response
	 *
	 * @SWG\Get(
	 *      path="/reservations/{id}",
	 *      summary="Display the  reservations by playground",
	 *      tags={"reservations"},
	 *      description="Get reservations",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="id",
	 *          description="id of reservations",
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
	 *                  ref="#/definitions/reservations"
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
		/** @var reservations $reservations */

		$reservations = reservations::with('playground')->with('time')->with('playground.user')->with('player')->where('pg_id', '=', $id)->get();
		if (empty($reservations)) {
			return $this->sendError('Reservations not found');
		}

		return $this->sendResponse($reservations->toArray(), 'Reservations retrieved successfully');
	}
	/**
	 * @param int $id
	 * @return Response
	 *
	 * @SWG\Get(
	 *      path="/reservations_by_player/{id}",
	 *      summary="Display the  reservations by player",
	 *      tags={"reservations"},
	 *      description="Get reservations",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="id",
	 *          description="id of reservations",
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
	 *                  ref="#/definitions/reservations"
	 *              ),
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string"
	 *              )
	 *          )
	 *      )
	 * )
	 */
	public function byplayer($id) {
		/** @var reservations $reservations */

		$reservations = reservations::with('playground')->with('time')->with('playground.user')->with('player')->where('player_id', '=', $id)->get();
		if (empty($reservations)) {
			return $this->sendError('Reservations not found');
		}

		return $this->sendResponse($reservations->toArray(), 'Reservations retrieved successfully');
	}

	/**
	 * @param int $id
	 * @param UpdatereservationsAPIRequest $request
	 * @return Response
	 *
	 * @SWG\Put(
	 *      path="/reservations/{id}",
	 *      summary="Update the specified reservations in storage",
	 *      tags={"reservations"},
	 *      description="Update reservations",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="id",
	 *          description="id of reservations",
	 *          type="integer",
	 *          required=true,
	 *          in="path"
	 *      ),
	 *      @SWG\Parameter(
	 *          name="body",
	 *          in="body",
	 *          description="reservations that should be updated",
	 *          required=false,
	 *          @SWG\Schema(ref="#/definitions/reservations")
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
	 *                  ref="#/definitions/reservations"
	 *              ),
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string"
	 *              )
	 *          )
	 *      )
	 * )
	 */
	public function update($id, UpdatereservationsAPIRequest $request) {
		$input = $request->all();

		/** @var reservations $reservations */
		$reservations = $this->reservationsRepository->findWithoutFail($id);

		if (empty($reservations)) {
			return $this->sendError('Reservations not found');
		}

		$reservations = $this->reservationsRepository->update($input, $id);

		return $this->sendResponse($reservations->toArray(), 'reservations updated successfully');
	}

	/**
	 * @param int $id
	 * @return Response
	 *
	 * @SWG\Delete(
	 *      path="/reservations/{id}",
	 *      summary="Remove the specified reservations from storage",
	 *      tags={"reservations"},
	 *      description="Delete reservations",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="id",
	 *          description="id of reservations",
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
		/** @var reservations $reservations */
		$reservations = $this->reservationsRepository->findWithoutFail($id);

		if (empty($reservations)) {
			return $this->sendError('Reservations not found');
		}

		$reservations->delete();

		return $this->sendResponse($id, 'Reservations deleted successfully');
	}

	/**
	 * @param CreatereservationsAPIRequest $request
	 * @return Response
	 *
	 * @SWG\Post(
	 *      path="/reservations/attendance",
	 *      summary="Set attendance for player on pg time for playground",
	 *      tags={"reservations"},
	 *      description="Set attendance for player on pg time for playground",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="body",
	 *          in="body",
	 *          description="reservations that should be stored",
	 *          required=false,
	 *          @SWG\Schema(ref="#/definitions/reservations")
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
	 *                  ref="#/definitions/reservations"
	 *              ),
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string"
	 *              )
	 *          )
	 *      )
	 * )
	 */
	public function setAttendance(CreatereservationsAPIRequest $request) {
		$request->merge(['attendance' => 1]);
		$input = $request->all();
		/** @var reservations $reservations */
		$reservations = $this->reservationsRepository->findWithoutFail($input['id']);

		if (empty($reservations)) {
			return $this->sendError('Reservations not found');
		}
		if ($reservations->attendance == 1) {
			return $this->sendError('Reservation already set as attendance');
		}

		$reservations       = $this->reservationsRepository->update($input, $input['id']);
		$reservations->code = 1;
		$user               = DB::table('users')->where('id', $reservations->player_id)->first();
		DB::table('users')
			->where('id', $reservations->player_id)
			->update(['points' => $user->points + 10]);
		return $this->sendResponse($reservations->toArray(), 'Attendance has been set successfully.');
	}

	/**
	 * @param CreatereservationsAPIRequest $request
	 * @return Response
	 *
	 * @SWG\Post(
	 *      path="/reservations/confirm",
	 *      summary="Set confirm for player on pg time for playground",
	 *      tags={"reservations"},
	 *      description="Set confirm for player on pg time for playground",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="body",
	 *          in="body",
	 *          description="reservations that should be stored",
	 *          required=false,
	 *          @SWG\Schema(ref="#/definitions/reservations")
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
	 *                  ref="#/definitions/reservations/"
	 *              ),
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string"
	 *              )
	 *          )
	 *      )
	 * )
	 */
	public function setConfirm(CreatereservationsAPIRequest $request) {
		$request->merge(['confirmed' => 1]);
		$input = $request->all();
		/** @var reservations $reservations */
		$reservations = $this->reservationsRepository->findWithoutFail($input['id']);

		if (empty($reservations)) {
			return $this->sendError('Reservations not found');
		}
		if ($reservations->confirmed == 1) {
			return $this->sendError('Reservation already set as confirmed');
		}

		/*=========================================
			        =            Send notification            =
		*/

		$pgtime = DB::table('pgtimes')->find($reservations->time_id);
		$pg     = DB::table('playgrounds')->find($reservations->pg_id);

		$onTime = date('Y-d-m h:m a', strtotime($pgtime->from_datetime));

		$arrEn   = array('am', 'pm');
		$arrAr   = array('ص', 'م');
		$fcmData = array(
			'title' => 'تم تأكيد حجز الملعب',
			'body'  => ' تم تأكيد حجز ملعب "' . $pg->pg_name . '" بتاريخ ' . str_replace($arrEn, $arrAr, $onTime),
		);

		$optionBuiler = new OptionsBuilder();
		$optionBuiler->setTimeToLive(60 * 20);

		$notificationBuilder = new PayloadNotificationBuilder($fcmData['title']);
		$notificationBuilder->setBody($fcmData['body'])
			->setSound('default');

		$dataBuilder = new PayloadDataBuilder();
		if (isset($fcmData['data']) && !empty($data['data'])) {
			foreach ($fcmData['data'] as $key => $value) {
				$dataBuilder->addData([$key => $value]);
			}
		}
		$option       = $optionBuiler->build();
		$notification = $notificationBuilder->build();
		$data         = $dataBuilder->build();
		$tokens_array = DB::table('users')
			->select('device_tokens')
			->whereNotNull('device_tokens')
			->where('id', $reservations->player_id)
			->pluck('device_tokens')->toArray();

		$tokens = @unserialize($tokens_array[0]);
		if ($tokens === false && $tokens_array[0] !== 'b:0;' || empty($tokens)) {
		} else {
			$downstreamResponse = FCM::sendTo($tokens, $option, $notification);
		}

		/*=====  End of Send notification  ======*/

		$reservations       = $this->reservationsRepository->update($input, $input['id']);
		$reservations->code = 1;
		return $this->sendResponse($reservations->toArray(), 'PG time has been confirmed successfully.');
	}

	/**
	 * @param Request $request
	 * @return Response
	 *
	 * @SWG\Post(
	 *      path="/reservations/cancel",
	 *      summary="Cancel reservation",
	 *      tags={"reservations"},
	 *      description="Cancel reservation",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="body",
	 *          in="body",
	 *          description="reservations that should be cancel",
	 *          required=false,
	 *          @SWG\Schema(ref="#/definitions/reservations/")
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
	 *                  ref="#/definitions/reservations/"
	 *              ),
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string"
	 *              )
	 *          )
	 *      )
	 * )
	 */
	public function cancelReservation(CreatereservationsAPIRequest $request) {

		if (isset($request->id)) {
			$reservations = DB::table('reservations')
				->where('id', $request->id)
				->first();
		}

		if (empty($reservations)) {
			return $this->sendError('Reservations not found');
		}

		if ( $reservations->deleted_at != null ){

			return $this->sendError("Reservation already canceled by owner." );
		}

		if ( $reservations->confirmed == 1 ) {
			return $this->sendError("Reservation already confirmed" );
		}
		$deletion = DB::table('reservations')
			->where('id', $reservations->id)
			->update(['deleted_at' => now()]);

		if ($deletion) {
			$pgtime = DB::table('pgtimes')
				->where('id', $reservations->time_id)
				->update(['booked' => 0]);
		}

		$pgtime = DB::table('pgtimes')->find($reservations->time_id);
		$pg     = DB::table('playgrounds')->find($reservations->pg_id);

		$onTime = date('Y-d-m h:m a', strtotime($pgtime->from_datetime));

		$arrEn   = array('am', 'pm');
		$arrAr   = array('ص', 'م');
		$fcmData = array(
			'title' => 'تم الغاء حجز الملعب',
			'body'  => ' تم الغاء حجز ملعب "' . $pg->pg_name . '" بتاريخ ' . str_replace($arrEn, $arrAr, $onTime),
		);

		$optionBuiler = new OptionsBuilder();
		$optionBuiler->setTimeToLive(60 * 20);

		$notificationBuilder = new PayloadNotificationBuilder($fcmData['title']);
		$notificationBuilder->setBody($fcmData['body'])
			->setSound('default');

		$dataBuilder = new PayloadDataBuilder();
		if (isset($fcmData['data']) && !empty($data['data'])) {
			foreach ($fcmData['data'] as $key => $value) {
				$dataBuilder->addData([$key => $value]);
			}
		}
		$option       = $optionBuiler->build();
		$notification = $notificationBuilder->build();
		$data         = $dataBuilder->build();
		$user         = ( isset($request->player) && !empty($request->player) && $request->player != 'pg_owner' )  ? $pg->user_id : $reservations->player_id;
		$tokens_array = DB::table('users')
			->select('device_tokens')
			->whereNotNull('device_tokens')
			->where('id', $user)
			->pluck('device_tokens')->toArray();
		$tokens = @unserialize($tokens_array[0]);
		if ($tokens === false && $tokens_array[0] !== 'b:0;' || empty($tokens)) {
		} else {
			$downstreamResponse = FCM::sendTo($tokens, $option, $notification);
		}

		return $this->sendResponse($reservations, 'Reservations has been deleted successfully and PG time is available.');
	}
}
