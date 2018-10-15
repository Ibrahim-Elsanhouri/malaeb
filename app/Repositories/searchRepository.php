<?php

namespace App\Repositories;

use App\Models\search;
use InfyOm\Generator\Common\BaseRepository;

class searchRepository extends BaseRepository
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
        return search::class;
    }
}
