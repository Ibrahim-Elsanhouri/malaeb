<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateprizesAPIRequest;
use App\Http\Requests\API\UpdateprizesAPIRequest;
use App\Library\MobilySmsService;
use App\Models\prizes;
use App\Repositories\prizesRepository;
use DB;
use Hash;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class prizesController
 * @package App\Http\Controllers\API
 */

class prizesAPIController extends AppBaseController {
		/** @var  prizesRepository */
	private $prizesRepository;

	public function __construct(prizesRepository $prizesRepo) {
		$this->prizesRepository = $prizesRepo;
	}
	/**
	 * @param Request $request
	 * @return Response
	 *
	 * @SWG\Get(
	 *      path="/prizes",
	 *      summary="Get a listing of the prizes.",
	 *      tags={"prizes"},
	 *      description="Get all prizes",
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
	 *                  @SWG\Items(ref="#/definitions/prizes")
	 *              ),
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string"
	 *              )
	 *          )
	 *      )
	 * )
	 */
	public function index(Request $request) {
		$this->prizesRepository->pushCriteria(new RequestCriteria($request));
		$this->prizesRepository->pushCriteria(new LimitOffsetCriteria($request));
		$prizes = $this->prizesRepository->all();

		return $this->sendResponse($prizes->toArray(), 'Prizes retrieved successfully');
	}

}