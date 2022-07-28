<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class PegawaiModel extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    JWTSubject
{
    use Authenticatable, Authorizable;
    protected $table = 'pegawai';
    protected $primaryKey = 'pegawai_id';
    public $timestamps = false;

    protected $hidden = ['password'];

    protected function getNameAttribute($value)
    {
        return strtolower($value);
    }

    public function children()
    {
        return $this->hasMany(
            'App\Models\PegawaiModel',
            'pegawai_position_id'
        )->select('pegawai_id', 'name');
    }

    public function gajian()
    {
        return $this->belongsTo(
            'App\Models\PenggajianModel',
            'users_id',
            'pegawai_id'
        );
    }

    public function parent()
    {
        return $this->belongsTo(
            'App\Models\PegawaiModel',
            'pegawai_position_id'
        )->select('pegawai_id', 'name');
    }

    public function getDepartemen()
    {
        return $this->belongsTo(
            'App\Models\Admin\DepartemenModel',
            'departemen_id'
        )->select('departemen_id', 'name');
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
