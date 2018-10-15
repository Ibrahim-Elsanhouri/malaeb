<?php

namespace App\DataTables;

use App\Models\homedata;
use Form;
use Yajra\Datatables\Services\DataTable;

class homedataDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'homedatas.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $homedatas = homedata::query();

        return $this->applyScopes($homedatas);
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
            'title' => ['name' => 'title', 'data' => 'title'],
            'image' => ['name' => 'image', 'data' => 'image'],
            'visitors' => ['name' => 'visitors', 'data' => 'visitors'],
            'booked_fields' => ['name' => 'booked_fields', 'data' => 'booked_fields'],
            'fields_added' => ['name' => 'fields_added', 'data' => 'fields_added']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'homedatas';
    }
}
