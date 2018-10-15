<?php

namespace App\Repositories;

use App\Models\reservations;
use InfyOm\Generator\Common\BaseRepository;

class reservationsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'pg_id',
        'time_id',
        'attendance',
        'player_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return reservations::class;
    }
}
