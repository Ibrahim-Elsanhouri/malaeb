<?php

namespace App\Http\Controllers;

use App\DataTables\pg_newsDataTable;
use App\Http\Requests;
use App\Http\Requests\Createpg_newsRequest;
use App\Http\Requests\Updatepg_newsRequest;
use App\Repositories\pg_newsRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class pg_newsController extends AppBaseController
{
    /** @var  pg_newsRepository */
    private $pgNewsRepository;

    public function __construct(pg_newsRepository $pgNewsRepo)
    {
        $this->pgNewsRepository = $pgNewsRepo;
    }

    /**
     * Display a listing of the pg_news.
     *
     * @param pg_newsDataTable $pgNewsDataTable
     * @return Response
     */
    public function index(pg_newsDataTable $pgNewsDataTable)
    {
        return $pgNewsDataTable->render('pg_news.index');
    }

    /**
     * Show the form for creating a new pg_news.
     *
     * @return Response
     */
    public function create()
    {
        return view('pg_news.create');
    }

    /**
     * Store a newly created pg_news in storage.
     *
     * @param Createpg_newsRequest $request
     *
     * @return Response
     */
    public function store(Createpg_newsRequest $request)
    {
        $input = $request->all();

        $pgNews = $this->pgNewsRepository->create($input);

        Flash::success('Pg News saved successfully.');

        return redirect(route('pgNews.index'));
    }

    /**
     * Display the specified pg_news.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $pgNews = $this->pgNewsRepository->findWithoutFail($id);

        if (empty($pgNews)) {
            Flash::error('Pg News not found');

            return redirect(route('pgNews.index'));
        }

        return view('pg_news.show')->with('pgNews', $pgNews);
    }

    /**
     * Show the form for editing the specified pg_news.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $pgNews = $this->pgNewsRepository->findWithoutFail($id);

        if (empty($pgNews)) {
            Flash::error('Pg News not found');

            return redirect(route('pgNews.index'));
        }

        return view('pg_news.edit')->with('pgNews', $pgNews);
    }

    /**
     * Update the specified pg_news in storage.
     *
     * @param  int              $id
     * @param Updatepg_newsRequest $request
     *
     * @return Response
     */
    public function update($id, Updatepg_newsRequest $request)
    {
        $pgNews = $this->pgNewsRepository->findWithoutFail($id);

        if (empty($pgNews)) {
            Flash::error('Pg News not found');

            return redirect(route('pgNews.index'));
        }

        $pgNews = $this->pgNewsRepository->update($request->all(), $id);

        Flash::success('Pg News updated successfully.');

        return redirect(route('pgNews.index'));
    }

    /**
     * Remove the specified pg_news from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $pgNews = $this->pgNewsRepository->findWithoutFail($id);

        if (empty($pgNews)) {
            Flash::error('Pg News not found');

            return redirect(route('pgNews.index'));
        }

        $this->pgNewsRepository->delete($id);

        Flash::success('Pg News deleted successfully.');

        return redirect(route('pgNews.index'));
    }
}
