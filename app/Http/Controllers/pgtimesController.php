<?php

namespace App\Http\Controllers;

use App\DataTables\pgtimesDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatepgtimesRequest;
use App\Http\Requests\UpdatepgtimesRequest;
use App\Repositories\pgtimesRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class pgtimesController extends AppBaseController
{
    /** @var  pgtimesRepository */
    private $pgtimesRepository;

    public function __construct(pgtimesRepository $pgtimesRepo)
    {
        $this->pgtimesRepository = $pgtimesRepo;
    }

    /**
     * Display a listing of the pgtimes.
     *
     * @param pgtimesDataTable $pgtimesDataTable
     * @return Response
     */
    public function index(pgtimesDataTable $pgtimesDataTable)
    {
        return $pgtimesDataTable->render('pgtimes.index');
    }

    /**
     * Show the form for creating a new pgtimes.
     *
     * @return Response
     */
    public function create()
    {
        return view('pgtimes.create');
    }

    /**
     * Store a newly created pgtimes in storage.
     *
     * @param CreatepgtimesRequest $request
     *
     * @return Response
     */
    public function store(CreatepgtimesRequest $request)
    {
        $input = $request->all();

        $pgtimes = $this->pgtimesRepository->create($input);

        Flash::success('Pgtimes saved successfully.');

        return redirect(route('pgtimes.index'));
    }

    /**
     * Display the specified pgtimes.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $pgtimes = $this->pgtimesRepository->findWithoutFail($id);

        if (empty($pgtimes)) {
            Flash::error('Pgtimes not found');

            return redirect(route('pgtimes.index'));
        }

        return view('pgtimes.show')->with('pgtimes', $pgtimes);
    }

    /**
     * Show the form for editing the specified pgtimes.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $pgtimes = $this->pgtimesRepository->findWithoutFail($id);

        if (empty($pgtimes)) {
            Flash::error('Pgtimes not found');

            return redirect(route('pgtimes.index'));
        }

        return view('pgtimes.edit')->with('pgtimes', $pgtimes);
    }

    /**
     * Update the specified pgtimes in storage.
     *
     * @param  int              $id
     * @param UpdatepgtimesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatepgtimesRequest $request)
    {
        $pgtimes = $this->pgtimesRepository->findWithoutFail($id);

        if (empty($pgtimes)) {
            Flash::error('Pgtimes not found');

            return redirect(route('pgtimes.index'));
        }

        $pgtimes = $this->pgtimesRepository->update($request->all(), $id);

        Flash::success('Pgtimes updated successfully.');

        return redirect(route('pgtimes.index'));
    }

    /**
     * Remove the specified pgtimes from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $pgtimes = $this->pgtimesRepository->findWithoutFail($id);

        if (empty($pgtimes)) {
            Flash::error('Pgtimes not found');

            return redirect(route('pgtimes.index'));
        }

        $this->pgtimesRepository->delete($id);

        Flash::success('Pgtimes deleted successfully.');

        return redirect(route('pgtimes.index'));
    }
}
