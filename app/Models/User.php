<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Profiler\Profile;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotificanion($token){
        $notification= new ResetPassword($token);
        $notification->createUrlUsing(function($user, $token){
            return url(route('user.password.reset', [
                'token'=> $token,
                'email'=> $user->email
            ]));
        });
        $this->notify($notification);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $value)->timezone('Europe/Moscow');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $value)->timezone('Europe/Moscow');
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function profiles(){
        return $this->hasMany(Profile::class);
    }
}
