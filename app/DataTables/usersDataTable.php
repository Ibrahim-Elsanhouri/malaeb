<?php

namespace App\DataTables;

use App\Models\users;
use Form;
use Yajra\Datatables\Services\DataTable;

class usersDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'users.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $users = users::query();

        return $this->applyScopes($users);
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
            'name' => ['name' => 'name', 'data' => 'name'],
            'mobile' => ['name' => 'mobile', 'data' => 'mobile'],
            'city' => ['name' => 'city', 'data' => 'city'],
            'area' => ['name' => 'area', 'data' => 'area'],
            'pg_type' => ['name' => 'pg_type', 'data' => 'pg_type'],
            'type' => ['name' => 'type', 'data' => 'type'],
            'team' => ['name' => 'team', 'data' => 'team'],
            'likes' => ['name' => 'likes', 'data' => 'likes'],
            'birth_date' => ['name' => 'birth_date', 'data' => 'birth_date'],
            'map_lon' => ['name' => 'map_lon', 'data' => 'map_lon'],
            'map_lat' => ['name' => 'map_lat', 'data' => 'map_lat'],
            'email' => ['name' => 'email', 'data' => 'email'],
            'password' => ['name' => 'password', 'data' => 'password'],
            'remember_token' => ['name' => 'remember_token', 'data' => 'remember_token'],
            'api_token' => ['name' => 'api_token', 'data' => 'api_token']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'users';
    }
}
