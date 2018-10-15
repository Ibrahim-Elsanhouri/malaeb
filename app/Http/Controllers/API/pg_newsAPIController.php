<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\Createpg_newsAPIRequest;
use App\Http\Requests\API\Updatepg_newsAPIRequest;
use App\Models\pg_news;
use App\Repositories\pg_newsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class pg_newsController
 * @package App\Http\Controllers\API
 */

class pg_newsAPIController extends AppBaseController
{
    /** @var  pg_newsRepository */
    private $pgNewsRepository;

    public function __construct(pg_newsRepository $pgNewsRepo)
    {
        $this->pgNewsRepository = $pgNewsRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/pg_news",
     *      summary="Get a listing of the pg_news.",
     *      tags={"pg_news"},
     *      description="Get all pg_news",
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
     *                  @SWG\Items(ref="#/definitions/pg_news")
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
        $this->pgNewsRepository->pushCriteria(new RequestCriteria($request));
        $this->pgNewsRepository->pushCriteria(new LimitOffsetCriteria($request));
        $pgNews = $this->pgNewsRepository->all();

        return $this->sendResponse($pgNews->toArray(), 'Pg News retrieved successfully');
    }

    /**
     * @param Createpg_newsAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/pg_news",
     *      summary="Store a newly created pg_news in storage",
     *      tags={"pg_news"},
     *      description="Store pg_news",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="pg_news that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/pg_news")
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
     *                  ref="#/definitions/pg_news"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(Createpg_newsAPIRequest $request)
    {
        $input = $request->all();

        $pgNews = $this->pgNewsRepository->create($input);

        return $this->sendResponse($pgNews->toArray(), 'Pg News saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/pg_news/{id}",
     *      summary="Display the specified pg_news",
     *      tags={"pg_news"},
     *      description="Get pg_news",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of pg_news",
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
     *                  ref="#/definitions/pg_news"
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
        /** @var pg_news $pgNews */
        $pgNews = $this->pgNewsRepository->findWithoutFail($id);
        $pgNews = pg_news::where('pg_id','=',"$id")->get();
        if (empty($pgNews)) {
            return $this->sendError('Pg News not found');
        }

        return $this->sendResponse($pgNews->toArray(), 'Pg News retrieved successfully');
    }

    /**
     * @param int $id
     * @param Updatepg_newsAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/pg_news/{id}",
     *      summary="Update the specified pg_news in storage",
     *      tags={"pg_news"},
     *      description="Update pg_news",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of pg_news",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="pg_news that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/pg_news")
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
     *                  ref="#/definitions/pg_news"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, Updatepg_newsAPIRequest $request)
    {
        $input = $request->all();

        /** @var pg_news $pgNews */
        $pgNews = $this->pgNewsRepository->findWithoutFail($id);

        if (empty($pgNews)) {
            return $this->sendError('Pg News not found');
        }

        $pgNews = $this->pgNewsRepository->update($input, $id);

        return $this->sendResponse($pgNews->toArray(), 'pg_news updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/pg_news/{id}",
     *      summary="Remove the specified pg_news from storage",
     *      tags={"pg_news"},
     *      description="Delete pg_news",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of pg_news",
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
        /** @var pg_news $pgNews */
        $pgNews = $this->pgNewsRepository->findWithoutFail($id);

        if (empty($pgNews)) {
            return $this->sendError('Pg News not found');
        }

        $pgNews->delete();

        return $this->sendResponse($id, 'Pg News deleted successfully');
    }
}
