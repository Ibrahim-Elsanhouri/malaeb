<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatearticlesAPIRequest;
use App\Http\Requests\API\UpdatearticlesAPIRequest;
use App\Models\articles;
use App\Repositories\articlesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class articlesController
 * @package App\Http\Controllers\API
 */

class articlesAPIController extends AppBaseController
{
    /** @var  articlesRepository */
    private $articlesRepository;

    public function __construct(articlesRepository $articlesRepo)
    {
        $this->articlesRepository = $articlesRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/articles",
     *      summary="Get a listing of the articles.",
     *      tags={"articles"},
     *      description="Get all articles",
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
     *                  @SWG\Items(ref="#/definitions/articles")
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
        $this->articlesRepository->pushCriteria(new RequestCriteria($request));
        $this->articlesRepository->pushCriteria(new LimitOffsetCriteria($request));
        $articles = $this->articlesRepository->all();

        return $this->sendResponse($articles->toArray(), 'Articles retrieved successfully');
    }

    /**
     * @param CreatearticlesAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/articles",
     *      summary="Store a newly created articles in storage",
     *      tags={"articles"},
     *      description="Store articles",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="articles that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/articles")
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
     *                  ref="#/definitions/articles"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreatearticlesAPIRequest $request)
    {
        $input = $request->all();

        $articles = $this->articlesRepository->create($input);

        return $this->sendResponse($articles->toArray(), 'Articles saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/articles/{id}",
     *      summary="Display the specified articles",
     *      tags={"articles"},
     *      description="Get articles",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of articles",
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
     *                  ref="#/definitions/articles"
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
        /** @var articles $articles */
        $articles = $this->articlesRepository->findWithoutFail($id);

        if (empty($articles)) {
            return $this->sendError('Articles not found');
        }

        return $this->sendResponse($articles->toArray(), 'Articles retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdatearticlesAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/articles/{id}",
     *      summary="Update the specified articles in storage",
     *      tags={"articles"},
     *      description="Update articles",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of articles",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="articles that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/articles")
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
     *                  ref="#/definitions/articles"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdatearticlesAPIRequest $request)
    {
        $input = $request->all();

        /** @var articles $articles */
        $articles = $this->articlesRepository->findWithoutFail($id);

        if (empty($articles)) {
            return $this->sendError('Articles not found');
        }

        $articles = $this->articlesRepository->update($input, $id);

        return $this->sendResponse($articles->toArray(), 'articles updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/articles/{id}",
     *      summary="Remove the specified articles from storage",
     *      tags={"articles"},
     *      description="Delete articles",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of articles",
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
        /** @var articles $articles */
        $articles = $this->articlesRepository->findWithoutFail($id);

        if (empty($articles)) {
            return $this->sendError('Articles not found');
        }

        $articles->delete();

        return $this->sendResponse($id, 'Articles deleted successfully');
    }
}
