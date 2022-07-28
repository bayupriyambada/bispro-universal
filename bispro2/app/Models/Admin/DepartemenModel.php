<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class DepartemenModel extends Model
{
    protected $table = 'departemen';
    protected $primaryKey = 'departemen_id';
    public $timestamps = false;

    protected function getNameAttribute($value)
    {
        return strtolower($value);
    }

    public function getUnitKerja()
    {
        return $this->belongsTo(
            'App\Models\Admin\UnitKerjaModel',
            'unit_kerja_id'
        );
    }
}
