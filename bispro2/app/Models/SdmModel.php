<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class SdmModel extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    JWTSubject
{
    use Authenticatable, Authorizable;
    protected $table = 'sdm';
    protected $primaryKey = 'sdm_id';
    public $timestamps = false;

    protected $hidden = ['password'];

    protected function getNameAttribute($value)
    {
        return strtolower($value);
    }

    public function getDepartemen()
    {
        return $this->belongsTo(
            'App\Models\Admin\DepartemenModel',
            'departemen_id'
        )->select('departemen_id', 'name');
    }

    public function getActiveAccountAttribute()
    {
        $this->attributes['active_account'] == 1
            ? ($verifikasiData = 'Active Account')
            : ($verifikasiData = 'Account Locked');
        return $verifikasiData;
    }
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
