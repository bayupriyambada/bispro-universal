<?php

namespace App\Http\Controllers\Pegawai;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Pegawai\DashboardRepository;

class DashboardController extends Controller
{
    public function getPersonalData()
    {
        $data = (new DashboardRepository())->getDataPersonal();
        return $data;
    }
}
