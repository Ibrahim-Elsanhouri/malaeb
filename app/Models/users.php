<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="users",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="mobile",
 *          description="mobile",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="city",
 *          description="city",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="area",
 *          description="area",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="pg_type",
 *          description="pg_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="type",
 *          description="player or pg_owner",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="team",
 *          description="forien key for team id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="likes",
 *          description="number of likes",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="birth_date",
 *          description="User birth date. Ex (2017-05-02)",
 *          type="string",
 *          format="date"
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
 *          property="email",
 *          description="email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="password",
 *          description="password",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="remember_token",
 *          description="remember_token",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="api_token",
 *          description="api_token",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="test",
 *          description="for testing purpose so no SMS will send and the confirmation  code is (1234)",
 *          type="string"
 *      )
 * )

 * @SWG\Definition(
 *      definition="login",
 *      required={""},
 *      @SWG\Property(
 *          property="mobile",
 *          description="mobile",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email",
 *          description="email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="password",
 *          description="password",
 *          type="string"
 *      )
 * )
 * @SWG\Definition(
 *      definition="confirm",
 *      required={"user_id, code"},
 *      @SWG\Property(
 *          property="user_id",
 *          description="user id",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="code",
 *          description="confirmation  code",
 *          type="string"
 *      )
 * )
 * @SWG\Definition(
 *      definition="logout",
 *      required={"user_id, code"},
 *      @SWG\Property(
 *          property="user_id",
 *          description="user id",
 *          type="string"
 *      ),
 * )
 * @SWG\Definition(
 *      definition="resend-code",
 *      required={"user_id"},
 *      @SWG\Property(
 *          property="user_id",
 *          description="user id",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="test",
 *          description="for testing purpose so no SMS will send and the confirmation  code is (1234)",
 *          type="string"
 *      )
 * )
 * @SWG\Definition(
 *      definition="forget-password",
 *      required={"mobile"},
 *      @SWG\Property(
 *          property="mobile",
 *          description="Valid user mobile",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="test",
 *          description="for testing purpose so no SMS will send and the new password will be in the response.",
 *          type="string"
 *      )
 * )
 * @SWG\Definition(
 *      definition="change-password",
 *      required={"user_id"},
 *      @SWG\Property(
 *          property="user_id",
 *          description="The user id",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="old_password",
 *          description="",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="new_password",
 *          description="",
 *          type="string"
 *      )
 * )
 * 
 * @SWG\Definition(
 *      definition="fb-login",
 *      required={"FB user ID"},
 *      @SWG\Property(
 *          property="fb_user_id",
 *          description="Facebook email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="mobile",
 *          description="User mobile number in case creating a new user",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="image",
 *          description="Facebook image url",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="test",
 *          description="for testing purpose so no SMS will send and the confirmation  code is (1234)",
 *          type="string"
 *      )
 * )
 * @SWG\Definition(
 *      definition="profile",
 *      required={"user_id"},
 *      @SWG\Property(
 *          property="user_id",
 *          description="User ID",
 *          type="integer"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="User name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="mobile",
 *          description="User mobile.",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="city",
 *          description="User city.",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="team",
 *          description="User team.",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="birth_date",
 *          description="User birth date. Ex (2017-05-02)",
 *          type="date"
 *      ),
 *      @SWG\Property(
 *          property="map_lon",
 *          description="User location longitude.",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="map_lat",
 *          description="User location latitude.",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="password",
 *          description="User password if you need to update.",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="image",
 *          description="User profile image in base64 formate.",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="snap_chat",
 *          description="user snap chat",
 *          type="string"
 *      )
 * )
 * @SWG\Definition(
 *      definition="points",
 *      required={"user_id", "points"},
 *      @SWG\Property(
 *          property="user_id",
 *          description="The user id",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="points",
 *          description="The user id points",
 *          type="int"
 *      )
 * )
 */
class users extends Model
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
        'logged_out',
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
