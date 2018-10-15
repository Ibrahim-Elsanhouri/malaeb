<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="reservations",
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
 *          property="time_id",
 *          description="time_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="player_id",
 *          description="player_id",
 *          type="integer",
 *          format="int32"
 *      )
 * )
 */
/**
 * @SWG\Definition(
 *      definition="reservations/attendance",
 *      required={"id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="attendance",
 *          description="attendance integer value 0 or 1",
 *          type="integer",
 *          format="int32"
 *      )
 * )
 */

/**
 * @SWG\Definition(
 *      definition="reservations/confirm",
 *      required={"id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="confirm",
 *          description="confirm integer value 0 or 1",
 *          type="integer",
 *          format="int32"
 *      )
 * )
 */


/**
 * @SWG\Definition(
 *      definition="reservations/cancel",
 *      required={"id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="string",
 *      ),
 *      @SWG\Property(
 *          property="time_id",
 *          description="the PG time id",
 *          type="string",
 *      )
 * )
 */
class reservations extends Model
{
    use SoftDeletes;

    public $table = 'reservations';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'pg_id',
        'time_id',
        'confirmed',
        'attendance',
        'player_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pg_id' => 'integer',
        'time_id' => 'integer',
        'player_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];
  public function time()
    {
        
      return $this->belongsTo(\App\Models\pgtimes::class,'time_id');
    }
  public function playground()
    {
        
        return $this->belongsTo(\App\Models\playgrounds::class,'pg_id');
    }
  public function player()
    {
        return $this->belongsTo(\App\Models\users::class,'player_id');
    }
  

    
}
