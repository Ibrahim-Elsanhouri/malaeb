<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatesearchAPIRequest;
use App\Http\Requests\API\UpdatesearchAPIRequest;
use App\Models\search;
use App\Repositories\searchRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use DB;
use Illuminate\Foundation\AliasLoader;
use App\Library\DatetimeHelper\DateTimeDiff;

/**
 * Class searchController
 * @package App\Http\Controllers\API
 */

class searchAPIController extends AppBaseController
{
    /** @var  searchRepository */
    private $searchRepository;

    public function __construct(searchRepository $searchRepo)
    {
        $this->searchRepository = $searchRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/search",
     *      summary="Get playgrounds as search results.",
     *      tags={"search"},
     *      description="Get playgrounds as search results",
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
     *                  @SWG\Items(ref="#/definitions/search")
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
        try{
            $query = DB::table('playgrounds')
                       ->select('playgrounds.id', 'playgrounds.pg_name', 'playgrounds.address', 'playgrounds.rating', 'playgrounds.pg_BookingNumbers', 'playgrounds.image', 'playgrounds.price')
                       ->distinct()
                       ->where('playgrounds.deleted_at', NULL);

            if ( isset( $request->pg_name ) ){
                $query-> where('pg_name', 'LIKE', '%'.$request->pg_name.'%');
            }

            if ( isset( $request->address ) ){
                $query-> where('city', $request->address );
            }

            if ( isset( $request->rating ) ){
                $query-> where('rating', '>=', $request->rating );
            }

            if ( isset( $request->date_from ) && isset( $request->date_to ) ){

                
                $query->join('pgtimes', 'playgrounds.id', '=', 'pgtimes.pg_id');
                $query-> whereBetween('pgtimes.from_datetime', [ $request->date_from, $request->date_to ] );
                $query-> where('pgtimes.deleted_at', NULL );

            }

            $playgrounds = $query->get()
                                 ->toArray();
            if ( !$playgrounds ){
                return $this->sendError('No playgrounds matched your search, please use different parameter');
            }
            return $this->sendResponse($playgrounds, 'Searches retrieved successfully');
        }catch(Exception $e){
            return $this->sendError('Something wrong, try again later');
        }
    }

   
}
