<?php

namespace App\Http\Controllers;

use App\DataTables\statisticsDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatestatisticsRequest;
use App\Http\Requests\UpdatestatisticsRequest;
use App\Repositories\statisticsRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class statisticsController extends AppBaseController
{
    /** @var  statisticsRepository */
    private $statisticsRepository;

    public function __construct(statisticsRepository $statisticsRepo)
    {
        $this->statisticsRepository = $statisticsRepo;
    }

    /**
     * Display a listing of the statistics.
     *
     * @param statisticsDataTable $statisticsDataTable
     * @return Response
     */
    public function index(statisticsDataTable $statisticsDataTable)
    {
        return $statisticsDataTable->render('statistics.index');
    }

    /**
     * Show the form for creating a new statistics.
     *
     * @return Response
     */
    public function create()
    {
        return view('statistics.create');
    }

    /**
     * Store a newly created statistics in storage.
     *
     * @param CreatestatisticsRequest $request
     *
     * @return Response
     */
    public function store(CreatestatisticsRequest $request)
    {
        $input = $request->all();

        $statistics = $this->statisticsRepository->create($input);

        Flash::success('Statistics saved successfully.');

        return redirect(route('statistics.index'));
    }

    /**
     * Display the specified statistics.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $statistics = $this->statisticsRepository->findWithoutFail($id);

        if (empty($statistics)) {
            Flash::error('Statistics not found');

            return redirect(route('statistics.index'));
        }

        return view('statistics.show')->with('statistics', $statistics);
    }

    /**
     * Show the form for editing the specified statistics.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $statistics = $this->statisticsRepository->findWithoutFail($id);

        if (empty($statistics)) {
            Flash::error('Statistics not found');

            return redirect(route('statistics.index'));
        }

        return view('statistics.edit')->with('statistics', $statistics);
    }

    /**
     * Update the specified statistics in storage.
     *
     * @param  int              $id
     * @param UpdatestatisticsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatestatisticsRequest $request)
    {
        $statistics = $this->statisticsRepository->findWithoutFail($id);

        if (empty($statistics)) {
            Flash::error('Statistics not found');

            return redirect(route('statistics.index'));
        }

        $statistics = $this->statisticsRepository->update($request->all(), $id);

        Flash::success('Statistics updated successfully.');

        return redirect(route('statistics.index'));
    }

    /**
     * Remove the specified statistics from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $statistics = $this->statisticsRepository->findWithoutFail($id);

        if (empty($statistics)) {
            Flash::error('Statistics not found');

            return redirect(route('statistics.index'));
        }

        $this->statisticsRepository->delete($id);

        Flash::success('Statistics deleted successfully.');

        return redirect(route('statistics.index'));
    }

    
}
