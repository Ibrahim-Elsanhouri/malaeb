<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="homedata",
 *      required={""},
 *      @SWG\Property(
 *          property="playgrounds",
 *          description="list of playgrounds",
 *          type="integer",
 *          format="string",
            type="array",
             @SWG\Items(ref="#/definitions/playgrounds")
 *      ),
 *      @SWG\Property(
 *          property="images",
 *          description="list of playgrounds",
 *          type="string",
            type="array",
            @SWG\Items(ref="#/definitions/pgimages")
            
 *      ),
 *      @SWG\Property(
 *          property="statistics",
 *          description="list of statistics",
 *          type="string",
            type="array",
            @SWG\Items(ref="#/definitions/statistics")
            
 *      )
 * )
 */
class homedata extends Model
{
    use SoftDeletes;

    public $table = 'homedatas';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'title',
        'image'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'image' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
