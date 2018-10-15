<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateplaygroundsAPIRequest;
use App\Http\Requests\API\UpdateplaygroundsAPIRequest;
use App\Models\playgrounds;
use App\Models\pgimages;
use App\Models\pg_news;
use App\Models\pgtimes;
use App\Models\Users;
use App\Repositories\playgroundsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use DB;
use Carbon\Carbon;
use Log;
/**
 * Class playgroundsController
 * @package App\Http\Controllers\API
 */

class playgroundsAPIController extends AppBaseController
{
    /** @var  playgroundsRepository */
    private $playgroundsRepository;

    public function __construct(playgroundsRepository $playgroundsRepo)
    {
        $this->playgroundsRepository = $playgroundsRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/playgrounds",
     *      summary="Get a listing of the playgrounds.",
     *      tags={"playgrounds"},
     *      description="Get all playgrounds",
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
     *                  @SWG\Items(ref="#/definitions/playgrounds")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     
     * )
     
     */
    public function index(Request $request)
    {
      
     // $id = 1;
      
        $this->playgroundsRepository->pushCriteria(new RequestCriteria($request));
        $this->playgroundsRepository->pushCriteria(new LimitOffsetCriteria($request));
        $playgrounds = $this->playgroundsRepository->all();
      $playgrounds =playgrounds::with('user')->get();
        return $this->sendResponse($playgrounds->toarray(), 'Playgrounds retrieved successfully');
    }

    /**
     * @param CreateplaygroundsAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/playgrounds",
     *      summary="Store a newly created playgrounds in storage",
     *      tags={"playgrounds"},
     *      description="Store playgrounds",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="playgrounds that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/playgrounds")
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
     *                  ref="#/definitions/playgrounds"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateplaygroundsAPIRequest $request)
    {
        $input = $request->all();
        unset($input['rating']);

        $playgrounds = $this->playgroundsRepository->create($input);

        return $this->sendResponse($playgrounds->toArray(), 'Playgrounds saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/playgrounds/{id}",
     *      summary="Display the specified playgrounds",
     *      tags={"playgrounds"},
     *      description="Get playgrounds",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of playgrounds",
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
     *                  ref="#/definitions/playgrounds"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var playgrounds $playgrounds */
        $playgrounds = $this->playgroundsRepository->findWithoutFail($id);
        $news = pg_news::where('pg_id','=',"$id")->get();
        $pgimages = pgimages::where('pg_id','=',"$id")->get();
      
        $data = array();
       
        $data['images']  = $data['news'] = $data['times']= array();
      //$pgtimes = pgtimes::::select('time', 'id', 'from_datetime')->where('pg_id','=',"$id")->where('booked','=',0)->get()->groupBy(function($pgtimes) {
     //   return Carbon::parse($pgtimes->from_datetime)->format('Y/m/d');
   // });
      $current_datetime = date("Y-m-d H:i", strtotime('+3 hours'));
      


     // echo $current_datetime ;die;
      $dates = DB::table('pgtimes')->where('pg_id','=',"$id")
               ->where('booked','=',0)
               ->where('parent_id','!=',0)
               ->where('deleted_at','=',null)
               ->where('from_datetime','>=',$current_datetime)
               ->select(DB::raw('DATE(from_datetime) as date'), DB::raw('count(*) as views'))
               ->groupBy('date')
               ->get()->toarray();
      // debug($dates);die;
      
      $bookeddates = DB::table('pgtimes')->where('pg_id','=',"$id")->where('booked','=',1)->where('parent_id','!=',0)
        ->where('deleted_at','=',null)->where('from_datetime','>=',$current_datetime)
      ->select(DB::raw('DATE(from_datetime) as date'), DB::raw('count(*) as views'))
      ->groupBy('date')
      ->get()->toarray();

      $i = 0;
      foreach($dates as $date){
        $data['times'][$i]['date'] =$date->date ;
        $data['times'][$i]['times']['am'] = pgtimes::where('pg_id','=',"$id")

                                           // ->whereDate('from_datetime','>=',$current_datetime)
                                           ->havingRaw("DATE_FORMAT(`from_datetime`,'%Y-%m-%d %H:%i') >= '$current_datetime'")
                                           ->where('booked','=',0)
                                           ->havingRaw("DATE_FORMAT(`from_datetime`,'%p') = 'am'")
                                           // ->havingRaw("DATE_FORMAT(`from_datetime`,'%Y-%m-%d') = '$date->date'")
                                           ->whereDate('from_datetime', '=', $date->date)
                                           ->get()
                                           ->toarray();


        $data['times'][$i]['times']['pm']   = pgtimes::where('pg_id','=',"$id")
        						    ->havingRaw("DATE_FORMAT(`from_datetime`,'%Y-%m-%d %H:%i') >= '$current_datetime'")
                                              // ->whereDate('from_datetime','>=',$current_datetime)
                                              ->where('booked','=',0)
                                              ->havingRaw("DATE_FORMAT(`from_datetime`,'%p') = 'pm'")
                                           // ->havingRaw("DATE_FORMAT(`from_datetime`,'%Y-%m-%d') = '$date->date'")
                                              ->whereDate('from_datetime', '=', $date->date)
                                              ->get()
                                              ->toarray();
      $i++;
      }
      

        if(!empty($times_am))
          $data['times']['am'] =  $times_am->toarray();
        if(!empty($times_pm))
          $data['times']['pm'] =  $times_pm->toarray();
        if(!empty($news))
          $data['news'] =  $news->toarray();
        if(!empty($pgimages))
          $data['images'] =  $pgimages->toarray();
         $times_msg = '';
        if(empty($data['times']) and empty($bookeddates)){
          $times_msg = "No times for this playground yet ,";
        }elseif(empty($data['times']) and !empty($bookeddates)){
          $times_msg = 'sorry , all times for this playground were booked';
        }
        if (empty($playgrounds)) {
            return $this->sendError('Playgrounds not found');
        }
        $pginfo = $playgrounds->toArray();
        $data['info']['ground_type'] = $pginfo['ground_type'];
        $data['info']['light_available'] = $pginfo['light_available'];
        $data['info']['football_available'] = $pginfo['football_available'];
        $data['info']['pg_numberoffields'] = $pginfo['pg_numberoffields'];
        $data['info']['times_msg'] = $times_msg;
        unset($pginfo['ground_type'],$pginfo['light_available'],$pginfo['football_available'],$pginfo['pg_numberoffields'],$pginfo['image']);
        $data['details'] = $pginfo;

        unset($input['rating']);
       // $data['info'] = $playgrounds->toArray();
      
       // $data['details'] = $playgrounds->toArray();
        //$result = array_merge($pginfo , $data);
        Log::info('app.requests', ['request' => $id, 'response' => $data]);
        return $this->sendResponse($data, 'Playgrounds retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateplaygroundsAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/playgrounds/{id}",
     *      summary="Update the specified playgrounds in storage",
     *      tags={"playgrounds"},
     *      description="Update playgrounds",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of playgrounds",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="playgrounds that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/playgrounds")
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
     *                  ref="#/definitions/playgrounds"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateplaygroundsAPIRequest $request)
    {
        $input = $request->all();
        /** @var playgrounds $playgrounds */
        $playgrounds = $this->playgroundsRepository->findWithoutFail($id);

        if (empty($playgrounds)) {
            return $this->sendError('Playgrounds not found');
        }

        $playgrounds = $this->playgroundsRepository->update($input, $id);

        return $this->sendResponse($playgrounds->toArray(), 'playgrounds updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/playgrounds/{id}",
     *      summary="Remove the specified playgrounds from storage",
     *      tags={"playgrounds"},
     *      description="Delete playgrounds",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of playgrounds",
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
        /** @var playgrounds $playgrounds */
        $playgrounds = $this->playgroundsRepository->findWithoutFail($id);

        if (empty($playgrounds)) {
            return $this->sendError('Playgrounds not found');
        }

        $playgrounds->delete();

        return $this->sendResponse($id, 'Playgrounds deleted successfully');
    }

       /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/playgrounds/rating",
     *      summary="Make rating for  a playground.",
     *      tags={"playgrounds"},
     *      description="Make rating for  a playground",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="user id ",
     *          required=true,
     *          @SWG\Schema(ref="#/definitions/playgrounds")
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
     *                  type="array",
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    
    public function makeRating(Request $request)
    {
        if ( !isset( $request->pg_id ) &&  !isset( $request->user_id ) && !isset( $request->value ) )
            return $this->sendError('the request can\'t be empty.');

        if ( !isset( $request->pg_id ) )
            return $this->sendError('Playground ID is required.');

        if ( !isset( $request->user_id ) )
            return $this->sendError('User ID is required.');

        if ( !isset( $request->value ) || ( 0 > $request->rating  || $request->value > 5 ) )
            return $this->sendError('A rating value number is required and should be from 0 to 5.');
        


        $pg = DB::table('playgrounds')->where('id', $request->pg_id)->first();
        if ( ! $pg )
            return $this->sendError('Playground not found');

        $user = DB::table('users')->where('id', $request->user_id)->first();
        if ( ! $user )
            return $this->sendError('User not found');

        try{
            $value = intval($request->value);
            $new_rating = $pg->rating + $value;
            DB::table('pg_rating')->insert(
                ['pg_id' => $request->pg_id, 'user_id' => $request->user_id, 'value' => $value]
            );
            $new_rating = DB::table('pg_rating')->avg('value');

            DB::table('playgrounds')->where('id', $request->pg_id)
             ->update(['rating' => round($new_rating, 1 ) ]);
            return $this->sendResponse(round($new_rating, 1 ) , 'Rating successfully added.');

        }catch(Exception $e){
            return $this->sendError('Can not submit rating.');
        }

    } 



       /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/playgrounds/nearby",
     *      summary="Get nearby playgrounds",
     *      tags={"playgrounds"},
     *      description="Get nearby playgrounds",
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
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    
    public function getNearby($lat, $lng)
    {
        if ( empty($lat) ){
            return $this->sendError('Latitude is required');
        }
        if ( empty($lng) ){
            return $this->sendError('Longitude is required');
        }

        try{
            $results = DB::select('SELECT *, ( 3959 * acos( cos( radians(?) ) * cos( radians( `map_lat` ) ) * 
                    cos( radians( `map_lon` ) - radians(?) ) + sin( radians(?) ) * 
                    sin( radians( `map_lat` ) ) ) ) AS distance FROM playgrounds HAVING
                    distance < 55 order by distance ;',[ $lat,$lng, $lat]);
            return $this->sendResponse($results, 'Nearby playgrounds retrieved successfully.');
        }catch(Exception $e){
            return $this->sendError('Something wrong, try again later');
        }


    }

}
