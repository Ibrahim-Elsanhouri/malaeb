<?php

namespace App\Repositories;

use App\Models\users;
use InfyOm\Generator\Common\BaseRepository;

class usersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'mobile',
        'city',
        'area',
        'pg_type',
        'type',
        'team',
        'likes',
        'birth_date',
        'map_lon',
        'map_lat',
        'email',
        'password',
        'remember_token',
        'api_token'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return users::class;
    }

    public function login(){
        return true;
    }
}
