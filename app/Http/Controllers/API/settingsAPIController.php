<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatesettingsAPIRequest;
use App\Http\Requests\API\UpdatesettingsAPIRequest;
use App\Models\settings;
use App\Repositories\settingsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class settingsController
 * @package App\Http\Controllers\API
 */

class settingsAPIController extends AppBaseController
{
    /** @var  settingsRepository */
    private $settingsRepository;

    public function __construct(settingsRepository $settingsRepo)
    {
        $this->settingsRepository = $settingsRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/settings",
     *      summary="Get a listing of the settings.",
     *      tags={"settings"},
     *      description="Get all settings",
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
     *                  @SWG\Items(ref="#/definitions/settings")
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
        $this->settingsRepository->pushCriteria(new RequestCriteria($request));
        $this->settingsRepository->pushCriteria(new LimitOffsetCriteria($request));
        $settings = $this->settingsRepository->all();

        return $this->sendResponse($settings->toArray(), 'Settings retrieved successfully');
    }

    /**
     * @param CreatesettingsAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/settings",
     *      summary="Store a newly created settings in storage",
     *      tags={"settings"},
     *      description="Store settings",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="settings that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/settings")
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
     *                  ref="#/definitions/settings"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreatesettingsAPIRequest $request)
    {
        $input = $request->all();

        $settings = $this->settingsRepository->create($input);

        return $this->sendResponse($settings->toArray(), 'Settings saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/settings/{id}",
     *      summary="Display the specified settings",
     *      tags={"settings"},
     *      description="Get settings",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of settings",
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
     *                  ref="#/definitions/settings"
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
        /** @var settings $settings */
        $settings = $this->settingsRepository->findWithoutFail($id);

        if (empty($settings)) {
            return $this->sendError('Settings not found');
        }

        return $this->sendResponse($settings->toArray(), 'Settings retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdatesettingsAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/settings/{id}",
     *      summary="Update the specified settings in storage",
     *      tags={"settings"},
     *      description="Update settings",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of settings",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="settings that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/settings")
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
     *                  ref="#/definitions/settings"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdatesettingsAPIRequest $request)
    {
        $input = $request->all();

        /** @var settings $settings */
        $settings = $this->settingsRepository->findWithoutFail($id);

        if (empty($settings)) {
            return $this->sendError('Settings not found');
        }

        $settings = $this->settingsRepository->update($input, $id);

        return $this->sendResponse($settings->toArray(), 'settings updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/settings/{id}",
     *      summary="Remove the specified settings from storage",
     *      tags={"settings"},
     *      description="Delete settings",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of settings",
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
        /** @var settings $settings */
        $settings = $this->settingsRepository->findWithoutFail($id);

        if (empty($settings)) {
            return $this->sendError('Settings not found');
        }

        $settings->delete();

        return $this->sendResponse($id, 'Settings deleted successfully');
    }
}
