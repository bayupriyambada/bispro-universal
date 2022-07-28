<?php

namespace App\Http\Controllers\Sdm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Sdm\DashboardRepository;

class DashboardController extends Controller
{
    public function getPersonalData()
    {
        $data = (new DashboardRepository())->getDataPersonal();
        return $data;
    }
}
