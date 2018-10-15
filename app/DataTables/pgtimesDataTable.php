<?php

namespace App\DataTables;

use App\Models\pgtimes;
use Form;
use Yajra\Datatables\Services\DataTable;

class pgtimesDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'pgtimes.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $pgtimes = pgtimes::query();

        return $this->applyScopes($pgtimes);
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
            'pg_id' => ['name' => 'pg_id', 'data' => 'pg_id'],
            'time' => ['name' => 'time', 'data' => 'time'],
            'am_pm' => ['name' => 'am_pm', 'data' => 'am_pm'],
            'from_datetime' => ['name' => 'from_datetime', 'data' => 'from_datetime'],
            'to_datetime' => ['name' => 'to_datetime', 'data' => 'to_datetime']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'pgtimes';
    }
}
