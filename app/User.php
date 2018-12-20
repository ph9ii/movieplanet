<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use App\Transformers\UserTransformer;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';

    const ADMIN_USER = 'true';
    const REGULAR_USER = 'false';

    public $transformer = UserTransformer::class;

    protected $table = 'users';

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($movie) {
            $movie->ratings->each->delete();
        });
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token',
        'verification_token',
    ];

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = strtolower($name);
    }

    public function getNameAttribute($name)
    {
        return ucwords($name);
    }

    public function SetEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
    * Check if admin
    * @return boolean
    */
    public function isAdmin()
    {
        return $this->admin == User::ADMIN_USER;
    }

    /**
    * Check if user is verified
    * @return boolean
    */
    public function isVerified()
    {
        return $this->verified == User::VERIFIED_USER;
    }

    /**
    * Generate a verification code
    * @return string
    */
    public static function generateVerificationCode()
    {
        return str_random(40);
    }
}
