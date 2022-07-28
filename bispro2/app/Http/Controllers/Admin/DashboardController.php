<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\DashboardRepository;

class DashboardController extends Controller
{
    public DashboardRepository $dashboard;

    public function __construct()
    {
        $this->dashboard = new DashboardRepository();
    }
    public function getPersonalData()
    {
        $data = $this->dashboard->getDataPersonal();
        return $data;
    }

    public function getCountTotalJenisCuti()
    {
        $data = $this->dashboard->getCountJenisCuti();
        return $data;
    }
    public function getCountTotalCuti()
    {
        $data = $this->dashboard->getCountCuti();
        return $data;
    }
    public function getCountTotalUnitKerja()
    {
        $data = $this->dashboard->getCountUnitKerja();
        return $data;
    }
    public function getCountTotalDepartemen()
    {
        $data = $this->dashboard->getCountDepartemen();
        return $data;
    }
    public function getCountTotalLembur()
    {
        $data = $this->dashboard->getCountLembur();
        return $data;
    }
    public function getCountTotalAbsensi()
    {
        $data = $this->dashboard->getCountAbsensi();
        return $data;
    }
    public function getCountTotalPerizinan()
    {
        $data = $this->dashboard->getCountPerizinan();
        return $data;
    }
    public function getCountTotalPegawai()
    {
        $data = $this->dashboard->getCountPegawai();
        return $data;
    }
    public function getCountTotalSdm()
    {
        $data = $this->dashboard->getCountSdm();
        return $data;
    }

    public function getCountTotalGaji()
    {
        $data = $this->dashboard->getCountGaji();
        return $data;
    }
}
