<?php

namespace App\Repositories;

use App\Models\cities;
use InfyOm\Generator\Common\BaseRepository;

class citiesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return cities::class;
    }
}
