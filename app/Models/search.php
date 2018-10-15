<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="search",
 *      required={""},
 *      @SWG\Property(
 *          property="pg_name",
 *          description="Play ground ID",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="address",
 *          description="Address, city, area",
 *          type="string",
 *          format="varchar"
 *      ),
 *      @SWG\Property(
 *          property="rating",
 *          description="play ground rating",
 *          type="string",
 *          format="varchar"
 *      ),
 *      @SWG\Property(
 *          property="date_from",
 *          description="Available from date",
 *          type="date",
 *          format="Y-m-d"
 *      ),
 *      @SWG\Property(
 *          property="date_to",
 *          description="Available to date",
 *          type="date",
 *          format="Y-m-d"
 *      )
 * )
 */
class search extends Model
{
    use SoftDeletes;

    public $table = 'playgrounds';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
