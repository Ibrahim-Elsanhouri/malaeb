<?php

namespace App\Http\Controllers;

use App\DataTables\homedataDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatehomedataRequest;
use App\Http\Requests\UpdatehomedataRequest;
use App\Repositories\homedataRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class homedataController extends AppBaseController
{
    /** @var  homedataRepository */
    private $homedataRepository;

    public function __construct(homedataRepository $homedataRepo)
    {
        $this->homedataRepository = $homedataRepo;
    }

    /**
     * Display a listing of the homedata.
     *
     * @param homedataDataTable $homedataDataTable
     * @return Response
     */
    public function index(homedataDataTable $homedataDataTable)
    {
        return $homedataDataTable->render('homedatas.index');
    }

    /**
     * Show the form for creating a new homedata.
     *
     * @return Response
     */
    public function create()
    {
        return view('homedatas.create');
    }

    /**
     * Store a newly created homedata in storage.
     *
     * @param CreatehomedataRequest $request
     *
     * @return Response
     */
    public function store(CreatehomedataRequest $request)
    {
        $input = $request->all();

        $homedata = $this->homedataRepository->create($input);

        Flash::success('Homedata saved successfully.');

        return redirect(route('homedatas.index'));
    }

    /**
     * Display the specified homedata.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $homedata = $this->homedataRepository->findWithoutFail($id);

        if (empty($homedata)) {
            Flash::error('Homedata not found');

            return redirect(route('homedatas.index'));
        }

        return view('homedatas.show')->with('homedata', $homedata);
    }

    /**
     * Show the form for editing the specified homedata.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $homedata = $this->homedataRepository->findWithoutFail($id);

        if (empty($homedata)) {
            Flash::error('Homedata not found');

            return redirect(route('homedatas.index'));
        }

        return view('homedatas.edit')->with('homedata', $homedata);
    }

    /**
     * Update the specified homedata in storage.
     *
     * @param  int              $id
     * @param UpdatehomedataRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatehomedataRequest $request)
    {
        $homedata = $this->homedataRepository->findWithoutFail($id);

        if (empty($homedata)) {
            Flash::error('Homedata not found');

            return redirect(route('homedatas.index'));
        }

        $homedata = $this->homedataRepository->update($request->all(), $id);

        Flash::success('Homedata updated successfully.');

        return redirect(route('homedatas.index'));
    }

    /**
     * Remove the specified homedata from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $homedata = $this->homedataRepository->findWithoutFail($id);

        if (empty($homedata)) {
            Flash::error('Homedata not found');

            return redirect(route('homedatas.index'));
        }

        $this->homedataRepository->delete($id);

        Flash::success('Homedata deleted successfully.');

        return redirect(route('homedatas.index'));
    }
}
