<?php

namespace App;
//use Laravel\Passport\HasApiTokens;

use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Notifications\Notifiable;
use Auth;

use Illuminate\Foundation\Auth\User as Authenticatable;




class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'mobile', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordController($token));
    }


    public function posts()
    {
        return $this->hasMany('App\Posts','author_id');
    }
    // user has many comments
    public function comments()
    {
        return $this->hasMany('App\Comments','from_user');
    }
    public function can_post()
    {
        $role = $this->type;
        if($role == 'player' || $role == 'pg_owner')
        {
            return true;
        }
        return false;
    }
    
   

}