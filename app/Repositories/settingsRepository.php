<?php

namespace App\Repositories;

use App\Models\settings;
use InfyOm\Generator\Common\BaseRepository;

class settingsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'key',
        'name',
        'description',
        'value',
        'field',
        'active'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return settings::class;
    }
}
