<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatepgimagesAPIRequest;
use App\Http\Requests\API\UpdatepgimagesAPIRequest;
use App\Models\pgimages;
use App\Repositories\pgimagesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class pgimagesController
 * @package App\Http\Controllers\API
 */

class pgimagesAPIController extends AppBaseController
{
    /** @var  pgimagesRepository */
    private $pgimagesRepository;

    public function __construct(pgimagesRepository $pgimagesRepo)
    {
        $this->pgimagesRepository = $pgimagesRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/pgimages",
     *      summary="Get a listing of the pgimages.",
     *      tags={"pgimages"},
     *      description="Get all pgimages",
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
     *                  @SWG\Items(ref="#/definitions/pgimages")
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
        $this->pgimagesRepository->pushCriteria(new RequestCriteria($request));
        $this->pgimagesRepository->pushCriteria(new LimitOffsetCriteria($request));
        $pgimages = $this->pgimagesRepository->all();

        return $this->sendResponse($pgimages->toArray(), 'Pgimages retrieved successfully');
    }

    /**
     * @param CreatepgimagesAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/pgimages",
     *      summary="Store a newly created pgimages in storage",
     *      tags={"pgimages"},
     *      description="Store pgimages",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="pgimages that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/pgimages")
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
     *                  ref="#/definitions/pgimages"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreatepgimagesAPIRequest $request)
    {
        $input = $request->all();

        $pgimages = $this->pgimagesRepository->create($input);

        return $this->sendResponse($pgimages->toArray(), 'Pgimages saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/pgimages/{id}",
     *      summary="Display the specified pgimages",
     *      tags={"pgimages"},
     *      description="Get pgimages",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of pgimages",
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
     *                  ref="#/definitions/pgimages"
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
        /** @var pgimages $pgimages */
        $pgimages = $this->pgimagesRepository->findWithoutFail($id);

        if (empty($pgimages)) {
            return $this->sendError('Pgimages not found');
        }

        return $this->sendResponse($pgimages->toArray(), 'Pgimages retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdatepgimagesAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/pgimages/{id}",
     *      summary="Update the specified pgimages in storage",
     *      tags={"pgimages"},
     *      description="Update pgimages",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of pgimages",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="pgimages that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/pgimages")
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
     *                  ref="#/definitions/pgimages"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdatepgimagesAPIRequest $request)
    {
        $input = $request->all();

        /** @var pgimages $pgimages */
        $pgimages = $this->pgimagesRepository->findWithoutFail($id);

        if (empty($pgimages)) {
            return $this->sendError('Pgimages not found');
        }

        $pgimages = $this->pgimagesRepository->update($input, $id);

        return $this->sendResponse($pgimages->toArray(), 'pgimages updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/pgimages/{id}",
     *      summary="Remove the specified pgimages from storage",
     *      tags={"pgimages"},
     *      description="Delete pgimages",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of pgimages",
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
        /** @var pgimages $pgimages */
        $pgimages = $this->pgimagesRepository->findWithoutFail($id);

        if (empty($pgimages)) {
            return $this->sendError('Pgimages not found');
        }

        $pgimages->delete();

        return $this->sendResponse($id, 'Pgimages deleted successfully');
    }
}
