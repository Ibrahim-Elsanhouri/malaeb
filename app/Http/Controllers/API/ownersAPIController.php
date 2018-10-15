<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\reservations;
use Illuminate\Http\Request;
use App\Models\playgrounds;
use App\Models\pgtimes;
use App\Models\users;
use App\Repositories\usersRepository;
use DB;
use Response;

/**
 * Class usersController
 * @package App\Http\Controllers\API
 */

class ownersAPIController extends AppBaseController {
	/** @var  usersRepository */
	private $usersRepository;

	public function __construct(usersRepository $usersRepo) {
		$this->usersRepository = $usersRepo;
	}


	/**
	 * @param int $user_id
	 * @return Response
	 *
	 * @SWG\Get(
	 *      path="/owners/playgrounds",
	 *      summary="List PG times for an owner",
	 *      tags={"owners"},
	 *      description="retrieve  PG times for an owner",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="user_id",
	 *          in="query",
	 *          description="user id",
	 *          type="integer",
	 *          required=true
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
	public function listPlaygrounds(Request $request) {
		if (!isset($request->user_id)) {
			return $this->sendError('Owner id is required.');
		}

		$user = Users::where('id', '=', "$request->user_id")->first();
		if (!$user) {
			return $this->sendError('Owner not exist.');
		}

		if ($user->type != 'pg_owner') {
			return $this->sendError('The user is not an owner');
		}
	      $playgrounds =playgrounds::where('user_id', $request->user_id)->get();
	      $playgrounds = $playgrounds->toArray();
		return $this->sendResponse($playgrounds , 'Playgrounds retrieved successfully');

	}


	/**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/owners/pgtimes",
     *      summary="Display the owner's specified playgrounds",
     *      tags={"owners"},
     *      description="Display the owner's specified playgrounds",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="user_id",
     *          description="user ID",
     *          required=true,
     *          in="query",
     *          type="integer"
     *      ),
     *      @SWG\Parameter(
     *          name="pg_id",
     *          description="playground ID",
     *          required=true,
     *          in="query",
     *          type="integer"
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
    public function showPlayground( Request $request )
    {
    	$user_id = $request->user_id;
	$pg_id = $request->pg_id;
    	if (!isset($user_id)) {
		return $this->sendError('Owner id is required.');
	}

	$user = Users::where('id', '=', "$user_id")->first();
	if (!$user) {
		return $this->sendError('Owner not exist.');
	}

	if ($user->type != 'pg_owner') {
		return $this->sendError('The user is not an owner');
	}


	$playground = playgrounds::where('id', '=', "$pg_id")->first();
	if (!$playground) {
		return $this->sendError('Playground dosn\'t exist.');
	}

	if ( $playground->user_id != $user_id ){
		return $this->sendError('The requested playground dosn\'t belong to the selected user.');
	}



	$data = array();
	$pginfo = $playground->toArray();
	$data['info']['pg_name'] = $pginfo['pg_name'];
	$data['info']['light_available'] = $pginfo['light_available'];
	$data['info']['football_available'] = $pginfo['football_available'];
	$data['info']['pg_numberoffields'] = $pginfo['pg_numberoffields'];
	$data['info']['times_msg'] = $times_msg;
	unset($pginfo['ground_type'],$pginfo['light_available'],$pginfo['football_available'],$pginfo['pg_numberoffields'],$pginfo['image']);
	$data['times']= array();
      //$pgtimes = pgtimes::::select('time', 'id', 'from_datetime')->where('pg_id','=',"$id")->where('booked','=',0)->get()->groupBy(function($pgtimes) {
     //   return Carbon::parse($pgtimes->from_datetime)->format('Y/m/d');
   // });
      $current_datetime = date("Y-m-d H:i");


     
      $dates = DB::table('pgtimes')->where('pg_id','=',"$pg_id")
               ->select('id', 'pg_id', 'time')
               ->where('deleted_at','=',null)
               ->where('from_datetime','>=',$current_datetime)
               ->select(DB::raw('DATE(from_datetime) as date'), DB::raw('count(*) as views'))
               ->groupBy('date')
               ->get()->toarray();

      // debug($dates);die;
      
      $bookeddates = DB::table('pgtimes')->where('pg_id','=',"$pg_id")->where('booked','=',1)->where('parent_id','!=',0)
        ->where('deleted_at','=',null)->where('from_datetime','>=',$current_datetime)
      ->select(DB::raw('DATE(from_datetime) as date'), DB::raw('count(*) as views'))
      ->groupBy('date')
      ->get()->toarray();

      $i = 0;
      $data['times']['not_booked'] = [];
      foreach($dates as $date){
      	if ( empty( $date ) )
      		continue;
      	/**
      	 * AM
      	 */
        $data['times']['not_booked'][$i]['date'] =$date->date ;
        $data['times']['not_booked'][$i]['times']['am'] = pgtimes::where('pg_id','=',"$pg_id")
        						 ->select('id', 'pg_id', 'time', 'from_datetime')
                                           ->where('pgtimes.deleted_at','=',null)
                                           ->havingRaw("DATE_FORMAT(`from_datetime`,'%Y-%m-%d %H:%i') >= '$current_datetime'")
                                           ->where('booked','=',0)
                                           ->havingRaw("DATE_FORMAT(`from_datetime`,'%p') = 'am'")
                                           ->whereDate('from_datetime', '=', $date->date)
                                           ->get()
                                           ->toarray();
	  $times = $data['times']['not_booked'][$i]['times']['am'];
	  if ( ! empty( $times ) )                                          
		  foreach ( $times as $key => $pgTime ) {	  	
		        $id = $pgTime['id'];
		        $reservation = reservations::where( 'time_id', $id )->first();
			  $data['times']['not_booked'][$i]['times']['am'][$key]['confirmed'] =  ( ! $reservation || $reservation->confirmed == 0  ) ? 0 : 1;
			  $data['times']['not_booked'][$i]['times']['am'][$key]['id'] = ( ! $reservation ) ? 0 : $reservation->id;
	  }



	  /**
	   * PM
	   */
        $data['times']['not_booked'][$i]['times']['pm']   = pgtimes::where('pg_id','=',"$pg_id")
        						    ->select('id', 'pg_id', 'time', 'from_datetime')
        						    ->where('pgtimes.deleted_at','=',null)
        						    ->havingRaw("DATE_FORMAT(`from_datetime`,'%Y-%m-%d %H:%i') >= '$current_datetime'")
                                              ->where('booked','=',0)
                                              ->havingRaw("DATE_FORMAT(`from_datetime`,'%p') = 'pm'")
                                              ->whereDate('from_datetime', '=', $date->date)
                                              ->get()
                                              ->toarray();

        $times = $data['times']['not_booked'][$i]['times']['pm'];


        if ( ! empty( $times ) )
		  foreach ( $times as $key => $pgTime ) {	  	
		        $id = $pgTime['id'];
		        $reservation = reservations::where( 'time_id', $id )->first();
			  $data['times']['not_booked'][$i]['times']['pm'][$key]['confirmed'] =  ( ! $reservation || $reservation->confirmed == 0  ) ? 0 : 1;
			  $data['times']['not_booked'][$i]['times']['pm'][$key]['id'] = ( ! $reservation ) ? 0 : $reservation->id;
		  }


        if ( empty( $data['times']['not_booked'][$i]['times']['pm'] ) && empty( $data['times']['not_booked'][$i]['times']['am'] ) ){
	  	unset( $data['times']['not_booked'][$i] );
	  }
      $i++;
      }
      /**
       * Booked
       * @var integer
       */
      $i = 0;
      $data['times']['booked'] = [];
      foreach($dates as $date){
      	if ( empty( $date ) )
      		continue;
      	/**
      	 * AM
      	 */
        $data['times']['booked'][$i]['date'] =$date->date ;
        $data['times']['booked'][$i]['times']['am'] = pgtimes::where('pgtimes.pg_id','=',"$pg_id")
        						    ->select('pgtimes.id', 'pgtimes.pg_id', 'pgtimes.time', 'pgtimes.from_datetime')
        						    ->where('pgtimes.deleted_at','=',null)
        						    ->havingRaw("DATE_FORMAT(`from_datetime`,'%Y-%m-%d %H:%i') >= '$current_datetime'")
                                              ->where('pgtimes.booked','=',1)
                                              ->havingRaw("DATE_FORMAT(`from_datetime`,'%p') = 'am'")
                                              ->whereDate('from_datetime', '=', $date->date)
                                              ->join('reservations', 'reservations.time_id', '=', 'pgtimes.id')
                                              ->where('reservations.attendance','!=',1)
                                              ->get()
                                              ->toarray();
	  $times = $data['times']['booked'][$i]['times']['am'];

	  if ( ! empty( $times ) )

		  foreach ( $times as $key => $pgTime ) {
		        $id = $pgTime['id'];
		        $reservation = reservations::where( 'time_id', $id )->first();
			  $data['times']['booked'][$i]['times']['am'][$key]['confirmed'] =  ( ! $reservation || $reservation->confirmed == 0  ) ? 0 : 1;
			  $data['times']['booked'][$i]['times']['am'][$key]['id'] = ( ! $reservation ) ? 0 : $reservation->id;
		  }



	  /**
	   * PM
	   */
        $data['times']['booked'][$i]['times']['pm']   = pgtimes::where('pgtimes.pg_id','=',"$pg_id")
        						    ->select('pgtimes.id', 'pgtimes.pg_id', 'pgtimes.time', 'pgtimes.from_datetime')
        						    ->where('pgtimes.deleted_at','=',null)
        						    ->havingRaw("DATE_FORMAT(`from_datetime`,'%Y-%m-%d %H:%i') >= '$current_datetime'")
                                              ->where('pgtimes.booked','=',1)
                                              ->havingRaw("DATE_FORMAT(`from_datetime`,'%p') = 'pm'")
                                              ->whereDate('from_datetime', '=', $date->date)
                                              ->join('reservations', 'reservations.time_id', '=', 'pgtimes.id')
                                              ->where('reservations.attendance','!=',1)
                                              ->get()
                                              ->toarray();

        $times = $data['times']['booked'][$i]['times']['pm'];
        if ( ! empty( $times ) )
	        foreach ( $times as $key => $pgTime ) {
		        $id = $pgTime['id'];
		        $reservation = reservations::where( 'time_id', $id )->first();
			  $data['times']['booked'][$i]['times']['pm'][$key]['confirmed'] =  ( ! $reservation || $reservation->confirmed == 0  ) ? 0 : 1;
			  $data['times']['booked'][$i]['times']['pm'][$key]['id'] = ( ! $reservation ) ? 0 : $reservation->id;
		  }

	  if ( empty( $data['times']['booked'][$i]['times']['pm'] ) && empty( $data['times']['booked'][$i]['times']['am'] ) ){
	  	unset( $data['times']['booked'][$i] );
	  }

      $i++;
      }
      $data['times']['booked'] = array_values($times = $data['times']['booked']);
      $data['times']['not_booked'] = array_values($times = $data['times']['not_booked']);
      
      

        if(!empty($times_am))
          $data['times']['am'] =  $times_am->toarray();
        if(!empty($times_pm))
          $data['times']['pm'] =  $times_pm->toarray();
         $times_msg = '';
        if(empty($data['times']) and empty($bookeddates)){
          $times_msg = "No times for this playground yet ,";
        }elseif(empty($data['times']) and !empty($bookeddates)){
          $times_msg = 'sorry , all times for this playground were booked';
        }
        
        unset($input['rating']);
       // $data['info'] = $playgrounds->toArray();
      
       // $data['details'] = $playgrounds->toArray();
        //$result = array_merge($pginfo , $data);
        return $this->sendResponse($data, 'Playgrounds retrieved successfully');
    }



	/**
	 * @param int $user_id
	 * @return Response
	 *
	 * @SWG\Get(
	 *      path="/owners/report",
	 *      summary="Revertive payments report",
	 *      tags={"owners"},
	 *      description="Revertive payments report",
	 *      produces={"application/json"},
	 *      @SWG\Parameter(
	 *          name="user_id",
	 *          in="query",
	 *          description="user id",
	 *          type="integer",
	 *          required=true
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
	public function pgReports(Request $request) {
		if (!isset($request->user_id)) {
			return $this->sendError('Owner id is required.');
		}

		$user = Users::where('id', '=', "$request->user_id")->first();
		if (!$user) {
			return $this->sendError('Owner not exist.');
		}

		if ($user->type != 'pg_owner') {
			return $this->sendError('The user is not an owner');
		}
	      $playgrounds =DB::table('playgrounds as p')
			->select('u.name','um.name as marketer', 'p.user_id', 'p.price', 'p.pg_name', 'p.id', DB::raw('COUNT(r.id) AS reserved'), DB::raw('p.price * COUNT(r.id)  AS total'))
			->leftJoin('reservations as r', 'p.id', '=', 'r.pg_id')
			->join('users as u', 'p.user_id', '=', 'u.id')
			->leftJoin('users as um', 'u.marketer_id', '=', 'um.id')
			->where('p.user_id', $request->user_id)
			->where('r.attendance', 1)
			->groupBy('p.id')
			->get();
			// debug($playgrounds);die;
	      if ( ! $playgrounds ){
	      	return $this->sendError('The requested owner doesn\'t has any playgrounds.');
	      }

	      $playgrounds = $playgrounds->toArray();

	      $data = [];
	      foreach ($playgrounds as $key => $pg) {
	      	$data[$key]['id'] = $pg->id;
	      	$data[$key]['pg_name'] = $pg->pg_name;
	      	$data[$key]['pg_BookingNumbers'] = $pg->reserved;
	      	$data[$key]['payments'] = $pg->total;

	      }
		return $this->sendResponse($data , 'Playgrounds payments report has been retrieved successfully.');

	}



}