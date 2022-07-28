<?php

namespace App\Models;

use App\Models\Admin\JenisCutiModel;
use Illuminate\Database\Eloquent\Model;

class CutiModel extends Model
{
    protected $table = 'cuti';
    public $incrementing = false;
    public $timestamps = false;

    public function jenisCuti()
    {
        return $this->belongsTo(
            'App\Models\Admin\JenisCutiModel',
            'jenis_cuti_id',
            'jenis_cuti_id'
        );
    }

    // public function getTotalCutiAttribute()
    // {
    //     if ($this->total_cuti > 0) {
    //         return $this->total_cuti;
    //     }
    // }
}
