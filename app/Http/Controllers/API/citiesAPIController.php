<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatecitiesAPIRequest;
use App\Http\Requests\API\UpdatecitiesAPIRequest;
use App\Models\cities;
use App\Repositories\citiesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use DB;
/**
 * Class citiesController
 * @package App\Http\Controllers\API
 */

class citiesAPIController extends AppBaseController
{
    /** @var  citiesRepository */
    private $citiesRepository;

    public function __construct(citiesRepository $citiesRepo)
    {
        $this->citiesRepository = $citiesRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/cities",
     *      summary="Get a listing of the cities.",
     *      tags={"cities"},
     *      description="Get all cities",
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
     *                  @SWG\Items(ref="#/definitions/cities")
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
        $this->citiesRepository->pushCriteria(new RequestCriteria($request));
        $this->citiesRepository->pushCriteria(new LimitOffsetCriteria($request));
        $cities = $this->citiesRepository->all();

        return $this->sendResponse($cities->toArray(), 'Cities retrieved successfully');
    }

   /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/cities/{id}",
     *      summary="Display the specified city",
     *      tags={"cities"},
     *      description="Get city",
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
     *                  ref="#/definitions/cities/"
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
        if ( empty($id) ){
            return $this->sendError('City id is required');
        }

        try{
            $city = DB::table('cities')->where('id', $id)->first();
            if ( empty ( $city ) ){
                return $this->sendError('City not found.');
            }
            return $this->sendResponse($city, 'City retrieved successfully.');
        }catch(Exception $e){
            return $this->sendError('Something wrong, try again later');
        }

    }


}
