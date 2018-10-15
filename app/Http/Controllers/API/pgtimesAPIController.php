<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatepgtimesAPIRequest;
use App\Http\Requests\API\UpdatepgtimesAPIRequest;
use App\Models\pgtimes;
use App\Repositories\pgtimesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class pgtimesController
 * @package App\Http\Controllers\API
 */

class pgtimesAPIController extends AppBaseController
{
    /** @var  pgtimesRepository */
    private $pgtimesRepository;

    public function __construct(pgtimesRepository $pgtimesRepo)
    {
        $this->pgtimesRepository = $pgtimesRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/pgtimes",
     *      summary="Get a listing of the pgtimes.",
     *      tags={"pgtimes"},
     *      description="Get all pgtimes",
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
     *                  @SWG\Items(ref="#/definitions/pgtimes")
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
        $this->pgtimesRepository->pushCriteria(new RequestCriteria($request));
        $this->pgtimesRepository->pushCriteria(new LimitOffsetCriteria($request));
        $pgtimes = $this->pgtimesRepository->all();

        return $this->sendResponse($pgtimes->toArray(), 'Pgtimes retrieved successfully');
    }

    /**
     * @param CreatepgtimesAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/pgtimes",
     *      summary="Store a newly created pgtimes in storage",
     *      tags={"pgtimes"},
     *      description="Store pgtimes",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="pgtimes that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/pgtimes")
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
     *                  ref="#/definitions/pgtimes"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreatepgtimesAPIRequest $request)
    {
        $input = $request->all();

        $pgtimes = $this->pgtimesRepository->create($input);

        return $this->sendResponse($pgtimes->toArray(), 'Pgtimes saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/pgtimes/{id}",
     *      summary="Display the specified pgtimes",
     *      tags={"pgtimes"},
     *      description="Get pgtimes",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of pgtimes",
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
     *                  ref="#/definitions/pgtimes"
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
        /** @var pgtimes $pgtimes */
        $pgtimes = $this->pgtimesRepository->findWithoutFail($id);
        
        $times_pg = pgtimes::where('pg_id','=',"$id")->where('booked','=',"0")->get()->toArray();
        $times['am'] = pgtimes::where('pg_id','=',"$id")->where('am_pm','=',"am")->where('booked','=',"0")->get()->toArray();
        $times['pm'] = pgtimes::where('pg_id','=',"$id")->where('am_pm','=',"pm")->where('booked','=',"0")->get()->toArray();
        if (empty($times_pg)) {
            return $this->sendError('Pgtimes not found');
        }

        return $this->sendResponse($times, 'Pgtimes retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdatepgtimesAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/pgtimes/{id}",
     *      summary="Update the specified pgtimes in storage",
     *      tags={"pgtimes"},
     *      description="Update pgtimes",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of pgtimes",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="pgtimes that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/pgtimes")
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
     *                  ref="#/definitions/pgtimes"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdatepgtimesAPIRequest $request)
    {
        $input = $request->all();

        /** @var pgtimes $pgtimes */
        $pgtimes = $this->pgtimesRepository->findWithoutFail($id);

        if (empty($pgtimes)) {
            return $this->sendError('Pgtimes not found');
        }

        $pgtimes = $this->pgtimesRepository->update($input, $id);

        return $this->sendResponse($pgtimes->toArray(), 'pgtimes updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/pgtimes/{id}",
     *      summary="Remove the specified pgtimes from storage",
     *      tags={"pgtimes"},
     *      description="Delete pgtimes",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of pgtimes",
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
        /** @var pgtimes $pgtimes */
        $pgtimes = $this->pgtimesRepository->findWithoutFail($id);

        if (empty($pgtimes)) {
            return $this->sendError('Pgtimes not found');
        }

        $pgtimes->delete();

        return $this->sendResponse($id, 'Pgtimes deleted successfully');
    }
}
