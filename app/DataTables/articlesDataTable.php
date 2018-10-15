<?php

namespace App\DataTables;

use App\Models\articles;
use Form;
use Yajra\Datatables\Services\DataTable;

class articlesDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'articles.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $articles = articles::query();

        return $this->applyScopes($articles);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->addAction(['width' => '10%'])
            ->ajax('')
            ->parameters([
                'dom' => 'Bfrtip',
                'scrollX' => false,
                'buttons' => [
                    'print',
                    'reset',
                    'reload',
                    [
                         'extend'  => 'collection',
                         'text'    => '<i class="fa fa-download"></i> Export',
                         'buttons' => [
                             'csv',
                             'excel',
                             'pdf',
                         ],
                    ],
                    'colvis'
                ]
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            'category_id' => ['name' => 'category_id', 'data' => 'category_id'],
            'title' => ['name' => 'title', 'data' => 'title'],
            'slug' => ['name' => 'slug', 'data' => 'slug'],
            'content' => ['name' => 'content', 'data' => 'content'],
            'image' => ['name' => 'image', 'data' => 'image'],
            'status' => ['name' => 'status', 'data' => 'status'],
            'date' => ['name' => 'date', 'data' => 'date'],
            'featured' => ['name' => 'featured', 'data' => 'featured']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'articles';
    }
}
