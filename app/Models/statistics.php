<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="statistics",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="visitors",
 *          description="visitors",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="booked_fields",
 *          description="booked_fields",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="fields_added",
 *          description="fields_added",
 *          type="integer",
 *          format="int32"
 *      )
 * )
 */
class statistics extends Model
{
    use SoftDeletes;

    public $table = 'statistics';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'visitors',
        'booked_fields',
        'fields_added'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'visitors' => 'integer',
        'booked_fields' => 'integer',
        'fields_added' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
