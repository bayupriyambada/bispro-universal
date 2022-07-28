<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenggajianModel extends Model
{
    protected $table = 'penggajian';
    public $incrementing = false;
    public $timestamps = false;

    public function pegawai()
    {
        return $this->hasMany(
            'App\Models\PegawaiModel',
            'pegawai_id',
            'users_id'
        )->select('pegawai_id', 'name', 'email');
    }
}
