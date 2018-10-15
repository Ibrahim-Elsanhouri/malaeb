<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatehomedataAPIRequest;
use App\Http\Requests\API\UpdatehomedataAPIRequest;
use App\Models\homedata;
use App\Models\playgrounds;
use App\Models\reservations;
use App\Models\users;
use App\Repositories\playgroundsRepository;
use DB;
use App\Models\statistics;
use App\Repositories\homedataRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class homedataController
 * @package App\Http\Controllers\API
 */

class homedataAPIController extends AppBaseController
{
    /** @var  homedataRepository */
    private $homedataRepository;
   private $playgroundsRepository;
    public function __construct(homedataRepository $homedataRepo,playgroundsRepository $playgroundsRepo )
    {
         $this->homedataRepository = $homedataRepo;
      $this->playgroundsRepository = $playgroundsRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/homedatas",
     *      summary="AAAGet a listing of the homedatas.",
     *      tags={"homedata"},
     *      description="Get all homedatas",
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
     *                  @SWG\Items(ref="#/definitions/homedata")
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
        $this->homedataRepository->pushCriteria(new RequestCriteria($request));
        $this->homedataRepository->pushCriteria(new LimitOffsetCriteria($request));
        $homedatas = array();
        if ( isset($request->user_id ) ){
            $user = DB::table('users')->where('id', $request->user_id)->first();
            if  ( $user->banned == 1 ){
                return $this->sendError('User is banned');
            }
            
            $pg_ids = DB::table('reservations')->select('reservations.pg_id')->distinct()
                        ->where('reservations.player_id', '=', $request->user_id)
                        ->where('reservations.attendance', '=', 1)
                        ->get()
                        ->toArray();


            $pg_a_not_rated = [];
            foreach ($pg_ids as $key => $pgid) {
                $user_pgs= DB::table('pg_rating')->select('pg_id')->distinct()
                  ->where('pg_id', $pgid->pg_id)
                  ->where('user_id', $request->user_id)
                  ->get()->toArray();
                  if ( empty( $user_pgs ) )
                    $pg_a_not_rated[] = $pgid->pg_id;

                
            }


            $pgs= DB::table('playgrounds')->select('id','pg_name', 'rating')

                  ->where('deleted_at', '=', NULL)
                  ->orderBy('rating','DESC')->limit(10)
                  ->whereIn('id', $pg_a_not_rated)->get()->toArray();

            $homedatas['nonrated_playgrounds'] = $pgs;
            $homedatas['user_img'] = $user->image;
        }
        $playgrounds = $this->playgroundsRepository->scopeQuery(function($query){
            return $query->orderBy('rating','DESC')->limit(10);
        })->all()->toarray();        
        $homedatas['playgrounds'] = $playgrounds;
        $homedatas['images'] = $this->homedataRepository->all()->toarray();
        $homedatas['statistics'] = array();
        $homedatas['statistics']['visitors'] = users::all()->count();
        $homedatas['statistics']['booked_fields'] = reservations::all()->count();
        $homedatas['statistics']['fields_added'] = playgrounds::all()->count();
        return $this->sendResponse($homedatas, 'Homedatas retrieved successfully');
    }

    public function store(CreatehomedataAPIRequest $request)
    {
        $input = $request->all();

        $homedatas = $this->homedataRepository->create($input);

        return $this->sendResponse($homedatas->toArray(), 'Homedata saved successfully');
    }

    public function show($id)
    {
        /** @var homedata $homedata */
        $homedata = $this->homedataRepository->findWithoutFail($id);

        if (empty($homedata)) {
            return $this->sendError('Homedata not found');
        }

        return $this->sendResponse($homedata->toArray(), 'Homedata retrieved successfully');
    }

    public function update($id, UpdatehomedataAPIRequest $request)
    {
        $input = $request->all();

        /** @var homedata $homedata */
        $homedata = $this->homedataRepository->findWithoutFail($id);

        if (empty($homedata)) {
            return $this->sendError('Homedata not found');
        }

        $homedata = $this->homedataRepository->update($input, $id);

        return $this->sendResponse($homedata->toArray(), 'homedata updated successfully');
    }

    public function destroy($id)
    {
        /** @var homedata $homedata */
        $homedata = $this->homedataRepository->findWithoutFail($id);

        if (empty($homedata)) {
            return $this->sendError('Homedata not found');
        }

        $homedata->delete();

        return $this->sendResponse($id, 'Homedata deleted successfully');
    }


    
 
}
