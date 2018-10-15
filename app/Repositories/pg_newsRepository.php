<?php

namespace App\Repositories;

use App\Models\pg_news;
use InfyOm\Generator\Common\BaseRepository;

class pg_newsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'pg_id',
        'title',
        'content'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return pg_news::class;
    }
}
