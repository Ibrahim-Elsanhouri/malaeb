<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="contact-us",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 * )
 */
class ContactUs extends Model
{
    // use SoftDeletes;

    public $table = 'contact_us';
    

    // protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'name',
        'email',
        'massage',
        'created_at'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
