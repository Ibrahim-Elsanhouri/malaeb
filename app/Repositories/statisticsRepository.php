<?php

namespace App\Repositories;

use App\Models\statistics;
use InfyOm\Generator\Common\BaseRepository;

class statisticsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'visitors',
        'booked_fields',
        'fields_added'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return statistics::class;
    }
}
