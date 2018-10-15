<?php

namespace App\Http\Controllers;

use App\DataTables\teamsDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateteamsRequest;
use App\Http\Requests\UpdateteamsRequest;
use App\Repositories\teamsRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class teamsController extends AppBaseController
{
    /** @var  teamsRepository */
    private $teamsRepository;

    public function __construct(teamsRepository $teamsRepo)
    {
        $this->teamsRepository = $teamsRepo;
    }

    /**
     * Display a listing of the teams.
     *
     * @param teamsDataTable $teamsDataTable
     * @return Response
     */
    public function index(teamsDataTable $teamsDataTable)
    {
        return $teamsDataTable->render('teams.index');
    }

    /**
     * Show the form for creating a new teams.
     *
     * @return Response
     */
    public function create()
    {
        return view('teams.create');
    }

    /**
     * Store a newly created teams in storage.
     *
     * @param CreateteamsRequest $request
     *
     * @return Response
     */
    public function store(CreateteamsRequest $request)
    {
        $input = $request->all();

        $teams = $this->teamsRepository->create($input);

        Flash::success('Teams saved successfully.');

        return redirect(route('teams.index'));
    }

    /**
     * Display the specified teams.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $teams = $this->teamsRepository->findWithoutFail($id);

        if (empty($teams)) {
            Flash::error('Teams not found');

            return redirect(route('teams.index'));
        }

        return view('teams.show')->with('teams', $teams);
    }

    /**
     * Show the form for editing the specified teams.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $teams = $this->teamsRepository->findWithoutFail($id);

        if (empty($teams)) {
            Flash::error('Teams not found');

            return redirect(route('teams.index'));
        }

        return view('teams.edit')->with('teams', $teams);
    }

    /**
     * Update the specified teams in storage.
     *
     * @param  int              $id
     * @param UpdateteamsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateteamsRequest $request)
    {
        $teams = $this->teamsRepository->findWithoutFail($id);

        if (empty($teams)) {
            Flash::error('Teams not found');

            return redirect(route('teams.index'));
        }

        $teams = $this->teamsRepository->update($request->all(), $id);

        Flash::success('Teams updated successfully.');

        return redirect(route('teams.index'));
    }

    /**
     * Remove the specified teams from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $teams = $this->teamsRepository->findWithoutFail($id);

        if (empty($teams)) {
            Flash::error('Teams not found');

            return redirect(route('teams.index'));
        }

        $this->teamsRepository->delete($id);

        Flash::success('Teams deleted successfully.');

        return redirect(route('teams.index'));
    }
}
