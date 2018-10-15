<?php

namespace App\Repositories;

use App\Models\playgrounds;
use InfyOm\Generator\Common\BaseRepository;

class playgroundsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'name',
        'address',
        'map_lon',
        'map_lat',
        'price',
        'featured',
        'image',
        'image2',
        'image3',
        'ground_type',
        'light_available',
        'football_available',
        'fields_count',
        'rating',
        'subtitle',
        'reservation_count'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return playgrounds::class;
    }
}
