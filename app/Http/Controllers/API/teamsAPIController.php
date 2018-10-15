<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateteamsAPIRequest;
use App\Http\Requests\API\UpdateteamsAPIRequest;
use App\Models\teams;
use App\Repositories\teamsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class teamsController
 * @package App\Http\Controllers\API
 */

class teamsAPIController extends AppBaseController
{
    /** @var  teamsRepository */
    private $teamsRepository;

    public function __construct(teamsRepository $teamsRepo)
    {
        $this->teamsRepository = $teamsRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/teams",
     *      summary="Get a listing of the teams.",
     *      tags={"teams"},
     *      description="Get all teams",
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
     *                  @SWG\Items(ref="#/definitions/teams")
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
        $this->teamsRepository->pushCriteria(new RequestCriteria($request));
        $this->teamsRepository->pushCriteria(new LimitOffsetCriteria($request));
        $teams = $this->teamsRepository->all();

        return $this->sendResponse($teams->toArray(), 'Teams retrieved successfully');
    }

    /**
     * @param CreateteamsAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/teams",
     *      summary="Store a newly created teams in storage",
     *      tags={"teams"},
     *      description="Store teams",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="teams that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/teams")
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
     *                  ref="#/definitions/teams"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateteamsAPIRequest $request)
    {
        $input = $request->all();

        $teams = $this->teamsRepository->create($input);

        return $this->sendResponse($teams->toArray(), 'Teams saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/teams/{id}",
     *      summary="Display the specified teams",
     *      tags={"teams"},
     *      description="Get teams",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of teams",
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
     *                  ref="#/definitions/teams"
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
        /** @var teams $teams */
        $teams = $this->teamsRepository->findWithoutFail($id);

        if (empty($teams)) {
            return $this->sendError('Teams not found');
        }

        return $this->sendResponse($teams->toArray(), 'Teams retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateteamsAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/teams/{id}",
     *      summary="Update the specified teams in storage",
     *      tags={"teams"},
     *      description="Update teams",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of teams",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="teams that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/teams")
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
     *                  ref="#/definitions/teams"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateteamsAPIRequest $request)
    {
        $input = $request->all();

        /** @var teams $teams */
        $teams = $this->teamsRepository->findWithoutFail($id);

        if (empty($teams)) {
            return $this->sendError('Teams not found');
        }

        $teams = $this->teamsRepository->update($input, $id);

        return $this->sendResponse($teams->toArray(), 'teams updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/teams/{id}",
     *      summary="Remove the specified teams from storage",
     *      tags={"teams"},
     *      description="Delete teams",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of teams",
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
        /** @var teams $teams */
        $teams = $this->teamsRepository->findWithoutFail($id);

        if (empty($teams)) {
            return $this->sendError('Teams not found');
        }

        $teams->delete();

        return $this->sendResponse($id, 'Teams deleted successfully');
    }
}
