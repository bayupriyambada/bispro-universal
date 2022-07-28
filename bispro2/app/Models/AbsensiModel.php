<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiModel extends Model
{
    protected $table = 'absensi';
    public $primaryKey = 'absensi_id';
    public $timestamps = false;

    public function getAbsensiIdAttributes()
    {
        return $this->attributes['absensi_id'] + 1;
    }
}
