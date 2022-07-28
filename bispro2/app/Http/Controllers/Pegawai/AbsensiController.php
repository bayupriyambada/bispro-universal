<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Repositories\Pegawai\AbsensiRepository;

class AbsensiController extends Controller
{
    public function getListData()
    {
        $data = (new AbsensiRepository())->getListData();
        return $data;
    }
    public function getInAbsensiIn()
    {
        $data = (new AbsensiRepository())->getAbsensiIn();
        return $data;
    }
    public function getInAbsensiOut()
    {
        $data = (new AbsensiRepository())->getAbsensiOut();
        return $data;
    }
}
