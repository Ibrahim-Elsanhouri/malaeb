<?php

namespace App\DataTables;

use App\Models\playgrounds;
use Form;
use Yajra\Datatables\Services\DataTable;

class playgroundsDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'playgrounds.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $playgrounds = playgrounds::query();

        return $this->applyScopes($playgrounds);
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
            'user_id' => ['name' => 'user_id', 'data' => 'user_id'],
            'name' => ['name' => 'name', 'data' => 'name'],
            'address' => ['name' => 'address', 'data' => 'address'],
            'map_lon' => ['name' => 'map_lon', 'data' => 'map_lon'],
            'map_lat' => ['name' => 'map_lat', 'data' => 'map_lat'],
            'price' => ['name' => 'price', 'data' => 'price'],
            'featured' => ['name' => 'featured', 'data' => 'featured'],
            'image' => ['name' => 'image', 'data' => 'image'],
            'image2' => ['name' => 'image2', 'data' => 'image2'],
            'image3' => ['name' => 'image3', 'data' => 'image3'],
            'ground_type' => ['name' => 'ground_type', 'data' => 'ground_type'],
            'light_available' => ['name' => 'light_available', 'data' => 'light_available'],
            'football_available' => ['name' => 'football_available', 'data' => 'football_available'],
            'fields_count' => ['name' => 'fields_count', 'data' => 'fields_count'],
            'rating' => ['name' => 'rating', 'data' => 'rating'],
            'subtitle' => ['name' => 'subtitle', 'data' => 'subtitle'],
            'reservation_count' => ['name' => 'reservation_count', 'data' => 'reservation_count']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'playgrounds';
    }
}
