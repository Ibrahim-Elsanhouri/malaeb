<?php

namespace App\Repositories;

use App\Models\pgimages;
use InfyOm\Generator\Common\BaseRepository;

class pgimagesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'pg_id',
        'image',
        'url'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return pgimages::class;
    }
}
