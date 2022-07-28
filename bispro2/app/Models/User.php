<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;

use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    JWTSubject
{
    use Authenticatable, Authorizable;
    // use Notifiable;
    // use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'users';
    protected $primaryKey = 'users_id';
    public $timestamps = false;
    // protected $fillable = [
    //     'name', 'email',
    // ];

    // /**
    //  * The attributes excluded from the model's JSON form.
    //  *
    //  * @var array
    //  */
    protected $hidden = ['password'];

    protected function getNameAttribute($value)
    {
        return strtolower($value);
    }

    // protected function getCreatedAtAttribute($value)
    // {
    //     return IndonesiaTimeHelpers::getIndonesiaTime($value);
    // }

    // protected function getUpdatedAtAttribute($value)
    // {
    //     return IndonesiaTimeHelpers::getIndonesiaTime($value);
    // }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
