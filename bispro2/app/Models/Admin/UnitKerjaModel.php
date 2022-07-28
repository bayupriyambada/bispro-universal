<?php

namespace App\Models\Admin;

use App\Helpers\IndonesiaTimeHelpers;
use Illuminate\Database\Eloquent\Model;

class UnitKerjaModel extends Model
{
    protected $table = 'unit_kerja';
    protected $primaryKey = 'unit_kerja_id';
    public $timestamps = false;

    protected function getNameAttribute($value)
    {
        return strtolower($value);
    }

    public function getDepartemen()
    {
        return $this->hasMany(
            'App\Models\Admin\DepartemenModel',
            'unit_kerja_id'
        );
    }
}
