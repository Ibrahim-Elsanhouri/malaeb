<?php

namespace App\Repositories;

use App\Models\teams;
use InfyOm\Generator\Common\BaseRepository;

class teamsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return teams::class;
    }
}
