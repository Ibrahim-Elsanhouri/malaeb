<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="prizes",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="img_unachieved",
 *          description="img_achieved",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="points",
 *          description="points",
 *          type="string"
 *      )
 * )
 */
class prizes extends Model
{
    use SoftDeletes;

    public $table = 'prizes';
    
    
    public $fillable = [
        'name',
        'img_achieved',
        'img_unachieved',
        'points'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'img_achieved' => 'string',
        'img_unachieved' => 'string',
        'points' => 'integer'
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
