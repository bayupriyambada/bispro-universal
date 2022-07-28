<?php

namespace App\Repositories\Sdm;

use App\Helpers\ResponseHelpers;
use App\Models\AbsensiModel;
use App\Models\CutiModel;
use App\Models\LemburModel;
use App\Models\PerizinanModel;

class VerificationDataRepository
{
    public function getCanVerificationAbsensi()
    {
        try {
            $data = AbsensiModel::query()
                ->where('verification_status', '=', 0)
                ->get();
            return ResponseHelpers::ResponseSuccess(
                200,
                'Success get data ' . $data->count() . ' data.',
                $data
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
    public function getCanVerificationLembur()
    {
        try {
            $data = LemburModel::query()
                ->where('verification_status', '=', 0)
                ->get();
            return ResponseHelpers::ResponseSuccess(
                200,
                'Success get data ' . $data->count() . ' data.',
                $data
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
    public function getCanVerificationCuti()
    {
        try {
            $data = CutiModel::query()
                ->where('verification_status', '=', 0)
                ->get();
            return ResponseHelpers::ResponseSuccess(
                200,
                'Success get data ' . $data->count() . ' data.',
                $data
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
    public function getCanVerificationPerizinan()
    {
        try {
            $data = PerizinanModel::query()
                ->where('verification_status', '=', 0)
                ->get();
            return ResponseHelpers::ResponseSuccess(
                200,
                'Success get data ' . $data->count() . ' data.',
                $data
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
}
