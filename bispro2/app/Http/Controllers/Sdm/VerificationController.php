<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\Controller;
use App\Repositories\Sdm\VerificationDataRepository;

class VerificationController extends Controller
{
    public function getCanVerificationAbsensi()
    {
        $data = (new VerificationDataRepository())->getCanVerificationAbsensi();
        return $data;
    }
    public function getCanVerificationLembur()
    {
        $data = (new VerificationDataRepository())->getCanVerificationLembur();
        return $data;
    }
    public function getCanVerificationCuti()
    {
        $data = (new VerificationDataRepository())->getCanVerificationCuti();
        return $data;
    }
    public function getCanVerificationPerizinan()
    {
        $data = (new VerificationDataRepository())->getCanVerificationPerizinan();
        return $data;
    }
}
