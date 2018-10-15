<?php

namespace App\Repositories;

use App\Models\prizes;
use InfyOm\Generator\Common\BaseRepository;

class prizesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'img_achieved',
        'img_unachieved',
        'points'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return prizes::class;
    }

    public function login(){
        return true;
    }
}
