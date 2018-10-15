<?php

namespace App\Http\Controllers;

use App\DataTables\articlesDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatearticlesRequest;
use App\Http\Requests\UpdatearticlesRequest;
use App\Repositories\articlesRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class articlesController extends AppBaseController
{
    /** @var  articlesRepository */
    private $articlesRepository;

    public function __construct(articlesRepository $articlesRepo)
    {
        $this->articlesRepository = $articlesRepo;
    }

    /**
     * Display a listing of the articles.
     *
     * @param articlesDataTable $articlesDataTable
     * @return Response
     */
    public function index(articlesDataTable $articlesDataTable)
    {
        return $articlesDataTable->render('articles.index');
    }

    /**
     * Show the form for creating a new articles.
     *
     * @return Response
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created articles in storage.
     *
     * @param CreatearticlesRequest $request
     *
     * @return Response
     */
    public function store(CreatearticlesRequest $request)
    {
        $input = $request->all();

        $articles = $this->articlesRepository->create($input);

        Flash::success('Articles saved successfully.');

        return redirect(route('articles.index'));
    }

    /**
     * Display the specified articles.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $articles = $this->articlesRepository->findWithoutFail($id);

        if (empty($articles)) {
            Flash::error('Articles not found');

            return redirect(route('articles.index'));
        }

        return view('articles.show')->with('articles', $articles);
    }

    /**
     * Show the form for editing the specified articles.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $articles = $this->articlesRepository->findWithoutFail($id);

        if (empty($articles)) {
            Flash::error('Articles not found');

            return redirect(route('articles.index'));
        }

        return view('articles.edit')->with('articles', $articles);
    }

    /**
     * Update the specified articles in storage.
     *
     * @param  int              $id
     * @param UpdatearticlesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatearticlesRequest $request)
    {
        $articles = $this->articlesRepository->findWithoutFail($id);

        if (empty($articles)) {
            Flash::error('Articles not found');

            return redirect(route('articles.index'));
        }

        $articles = $this->articlesRepository->update($request->all(), $id);

        Flash::success('Articles updated successfully.');

        return redirect(route('articles.index'));
    }

    /**
     * Remove the specified articles from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $articles = $this->articlesRepository->findWithoutFail($id);

        if (empty($articles)) {
            Flash::error('Articles not found');

            return redirect(route('articles.index'));
        }

        $this->articlesRepository->delete($id);

        Flash::success('Articles deleted successfully.');

        return redirect(route('articles.index'));
    }
}
