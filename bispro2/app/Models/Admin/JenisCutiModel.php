<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class JenisCutiModel extends Model
{
    protected $table = 'jenis_cuti';
    protected $primaryKey = 'jenis_cuti_id';
    public $timestamps = false;

    protected function getNameAttribute($value)
    {
        return strtolower($value);
    }

    public function cuti()
    {
        return $this->hasMany('App\Models\CutiModel', 'jenis_cuti_id');
    }
}
