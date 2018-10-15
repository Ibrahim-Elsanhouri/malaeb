<?php

namespace App\Repositories;

use App\Models\articles;
use InfyOm\Generator\Common\BaseRepository;

class articlesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'category_id',
        'title',
        'slug',
        'content',
        'image',
        'status',
        'date',
        'featured'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return articles::class;
    }
}
