<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="pgtimes",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="pg_id",
 *          description="pg_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="time",
 *          description="time",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="am_pm",
 *          description="am_pm",
 *          type="string"
 *      )
 * )
 */
class pgtimes extends Model
{
    use SoftDeletes;

    public $table = 'pgtimes';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at','expired_at'];
/**
 * The attributes that should be mutated to dates.
 *
 * @var array
 */


    public $fillable = [
        'pg_id',
        'time',
        'am_pm',
        'from_datetime',
        'to_datetime'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pg_id' => 'integer',
        'time' => 'string',
        'am_pm' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];
  

    
}
