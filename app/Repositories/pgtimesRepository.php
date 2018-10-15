<?php

namespace App\Repositories;

use App\Models\pgtimes;
use InfyOm\Generator\Common\BaseRepository;

class pgtimesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'pg_id',
        'time',
        'am_pm',
        'from_datetime',
        'to_datetime'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return pgtimes::class;
    }
}
