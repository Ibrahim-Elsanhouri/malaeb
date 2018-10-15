<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class owners extends Model
{
    use SoftDeletes;

    public $table = 'users';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'mobile',
        'city',
        'area',
        'pg_type',
        'type',
        'team',
        'position',
        'points',
        'birth_date',
        'snap_chat',
        'image',
        'map_lon',
        'map_lat',
        'email',
        'password',
        'remember_token',
        'api_token',
        'fb_user_id',
        'banned',
        'confirmed'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'mobile' => 'string',
        'city' => 'string',
        'area' => 'string',
        'pg_type' => 'string',
        'type' => 'string',
        'team' => 'string',
        'position' => 'string',
        'points' => 'integer',
        'birth_date' => 'date',
        'image' => 'string',
        'email' => 'string',
        'snap_chat' => 'string',
        'password' => 'string',
        'remember_token' => 'string',
        'fb_user_id' => 'string',
        'banned' => 'integer',
        'api_token' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
  //  'name' => 'required',
  //  'city' => 'required',
 //   'area' => 'required',
//    'pg_type' => 'required',

    ];

   

}
