<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\Controller;
use App\Repositories\Sdm\VerifiedRepository;

class VerifiedController extends Controller
{
    public function getVerificationAbsensi()
    {
        $data = (new VerifiedRepository())->getVerificationAbsensi();
        return $data;
    }
    public function getVerificationLembur()
    {
        $data = (new VerifiedRepository())->getVerificationLembur();
        return $data;
    }
    public function getVerificationCuti()
    {
        $data = (new VerifiedRepository())->getVerificationCuti();
        return $data;
    }
    public function getVerificationPerizinan()
    {
        $data = (new VerifiedRepository())->getVerificationPerizinan();
        return $data;
    }
}
