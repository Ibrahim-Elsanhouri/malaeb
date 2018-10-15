<?php

namespace App\Repositories;

use App\Models\homedata;
use InfyOm\Generator\Common\BaseRepository;

class homedataRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'image',
        'visitors',
        'booked_fields',
        'fields_added'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return homedata::class;
    }
}
