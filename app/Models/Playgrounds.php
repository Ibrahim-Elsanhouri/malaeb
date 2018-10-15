<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="playgrounds",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="pg_name",
 *          description="pg_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="address",
 *          description="address",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="map_lon",
 *          description="map_lon",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="map_lat",
 *          description="map_lat",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="price",
 *          description="price",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="featured",
 *          description="featured",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="ground_type",
 *          description="ground_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="light_available",
 *          description="light_available",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="football_available",
 *          description="football_available",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="subtitle",
 *          description="subtitle",
 *          type="string"
 *      )
 * )
 *  * @SWG\Definition(
 *      definition="playgrounds/rating",
 *      required={"pg_id,user_id,value"},
 *      @SWG\Property(
 *          property="pg_id",
 *          description="Playground ID",
 *          type="integer"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="User ID",
 *          type="integer"
 *      ),
 *      @SWG\Property(
 *          property="value",
 *          description="Rating value",
 *          type="integer"
 *      )
 * )
 *
 * @SWG\Definition(
 *      definition="playgrounds/nearby",
 *      required={"lat,lng"},
 *      @SWG\Property(
 *          property="lat",
 *          description="Latitude",
 *          type="integer"
 *      ),
 *      @SWG\Property(
 *          property="lng",
 *          description="Longitude",
 *          type="integer"
 *      )
 * )
 */
class playgrounds extends Model
{
    use SoftDeletes;


    public $table = 'playgrounds';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'user_id',
        'pg_name',
        'address',
        'map_lon',
        'map_lat',
        'price',
        'featured',
        'image',
        'ground_type',
        'light_available',
        'football_available',
        'fields_count',
        'rating',
        'subtitle'
        ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'name' => 'string',
        'address' => 'string',
        'featured' => 'boolean',
        'image' => 'string',
        'image2' => 'string',
        'image3' => 'string',
        'ground_type' => 'string',
        'light_available' => 'integer',
        'football_available' => 'integer',
        'fields_count' => 'string',
        'rating' => 'integer',
        'subtitle' => 'string',
    ];

    public $timestamps = false;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];
  public function user()
    {
        return $this->belongsTo(\App\Models\users::class,'user_id');
    }

    
}
