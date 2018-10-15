<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatestatisticsAPIRequest;
use App\Http\Requests\API\UpdatestatisticsAPIRequest;
use App\Models\statistics;
use App\Repositories\statisticsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class statisticsController
 * @package App\Http\Controllers\API
 */

class statisticsAPIController extends AppBaseController
{
    /** @var  statisticsRepository */
    private $statisticsRepository;

    public function __construct(statisticsRepository $statisticsRepo)
    {
        $this->statisticsRepository = $statisticsRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/statistics",
     *      summary="Get a listing of the statistics.",
     *      tags={"statistics"},
     *      description="Get all statistics",
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
     *                  @SWG\Items(ref="#/definitions/statistics")
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
        $this->statisticsRepository->pushCriteria(new RequestCriteria($request));
        $this->statisticsRepository->pushCriteria(new LimitOffsetCriteria($request));
        $statistics = $this->statisticsRepository->all();

        return $this->sendResponse($statistics->toArray(), 'Statistics retrieved successfully');
    }

    /**
     * @param CreatestatisticsAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/statistics",
     *      summary="Store a newly created statistics in storage",
     *      tags={"statistics"},
     *      description="Store statistics",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="statistics that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/statistics")
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
     *                  ref="#/definitions/statistics"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreatestatisticsAPIRequest $request)
    {
        $input = $request->all();

        $statistics = $this->statisticsRepository->create($input);

        return $this->sendResponse($statistics->toArray(), 'Statistics saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/statistics/{id}",
     *      summary="Display the specified statistics",
     *      tags={"statistics"},
     *      description="Get statistics",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of statistics",
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
     *                  ref="#/definitions/statistics"
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
        /** @var statistics $statistics */
        $statistics = $this->statisticsRepository->findWithoutFail($id);

        if (empty($statistics)) {
            return $this->sendError('Statistics not found');
        }

        return $this->sendResponse($statistics->toArray(), 'Statistics retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdatestatisticsAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/statistics/{id}",
     *      summary="Update the specified statistics in storage",
     *      tags={"statistics"},
     *      description="Update statistics",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of statistics",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="statistics that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/statistics")
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
     *                  ref="#/definitions/statistics"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdatestatisticsAPIRequest $request)
    {
        $input = $request->all();

        /** @var statistics $statistics */
        $statistics = $this->statisticsRepository->findWithoutFail($id);

        if (empty($statistics)) {
            return $this->sendError('Statistics not found');
        }

        $statistics = $this->statisticsRepository->update($input, $id);

        return $this->sendResponse($statistics->toArray(), 'statistics updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/statistics/{id}",
     *      summary="Remove the specified statistics from storage",
     *      tags={"statistics"},
     *      description="Delete statistics",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of statistics",
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
        /** @var statistics $statistics */
        $statistics = $this->statisticsRepository->findWithoutFail($id);

        if (empty($statistics)) {
            return $this->sendError('Statistics not found');
        }

        $statistics->delete();

        return $this->sendResponse($id, 'Statistics deleted successfully');
    }
}
