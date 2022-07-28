<?php

namespace App\Repositories\Pegawai;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\AbsensiModel;
use App\Helpers\ResponseHelpers;
use App\Helpers\IndonesiaTimeHelpers;

class AbsensiRepository
{
    public function getListData()
    {
        try {
            $dateFirst = IndonesiaTimeHelpers::getDateIndonesia(
                Carbon::now()->startOfMonth()
            );
            $dateLast = IndonesiaTimeHelpers::getDateIndonesia(
                Carbon::now()->endOfMonth()
            );
            $data = AbsensiModel::query()
                ->selectRaw('*, ROW_NUMBER() over(ORDER BY absensi_id) nomor')
                ->whereBetween('date', [$dateFirst, $dateLast])
                ->where(
                    'users_id',
                    auth()
                        ->guard('api_pegawai')
                        ->user()->pegawai_id
                )
                ->whereNull('deleted_at')
                ->get();

            return ResponseHelpers::ResponseSuccess(
                200,
                'Success get data absensi ' .
                    auth()
                        ->guard('api_pegawai')
                        ->user()->name,
                $data
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }

    public function getAbsensiIn()
    {
        try {
            $date = Carbon::now();
            $today = IndonesiaTimeHelpers::getDateIndonesia($date);
            $data = AbsensiModel::query()
                ->where(
                    'users_id',
                    '=',
                    auth()
                        ->guard('api_pegawai')
                        ->user()->pegawai_id
                )
                ->where('date', '=', $today)
                ->first();
            if (!is_null($data)) {
                return ResponseHelpers::ResponseSuccess(
                    200,
                    'Success get data absensi today ',
                    $data->date . ' ' . $data->time_in
                );
            } else {
                $absensiId = new AbsensiModel();
                $absensiId->date = IndonesiaTimeHelpers::getDateIndonesia(
                    $date
                );
                $absensiId->time_in = IndonesiaTimeHelpers::getTimeIndonesia(
                    $date
                );
                $absensiId->users_id = auth()
                    ->guard('api_pegawai')
                    ->user()->pegawai_id;
                $absensiId->created_at = IndonesiaTimeHelpers::getIndonesiaTime(
                    Carbon::now()
                );
                $absensiId->save();
                return ResponseHelpers::ResponseSuccess(
                    200,
                    'Success get absensi in',
                    'You are absent on :' .
                        $absensiId->date .
                        ' ' .
                        $absensiId->time_in
                );
            }
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
    public function getAbsensiOut()
    {
        try {
            $date = Carbon::now();
            $today = IndonesiaTimeHelpers::getDateIndonesia($date);
            $data = AbsensiModel::query()
                ->where(
                    'users_id',
                    '=',
                    auth()
                        ->guard('api_pegawai')
                        ->user()->pegawai_id
                )
                ->where('date', '=', $today)
                ->first();
            if (!$data) {
                return ResponseHelpers::ResponseError(
                    'You are not absent today',
                    400
                );
            } else {
                $data = AbsensiModel::where('time_out', '=', null)->first();
                if ($data) {
                    $data->time_out = IndonesiaTimeHelpers::getTimeIndonesia(
                        $date
                    );

                    $data->updated_at = IndonesiaTimeHelpers::getIndonesiaTime(
                        Carbon::now()
                    );
                    $data->users_id = auth()
                        ->guard('api_pegawai')
                        ->user()->pegawai_id;

                    $data->duration_time = Carbon::parse(
                        $data->time_in
                    )->diffInHours(Carbon::parse($data->time_out));
                    $data->save();
                    return ResponseHelpers::ResponseSuccess(
                        200,
                        'Success get absensi out',
                        'You are absent on :' .
                            $data->date .
                            ' ' .
                            $data->time_out
                    );
                } else {
                    return ResponseHelpers::ResponseError(
                        'You are not absent today',
                        400
                    );
                }
            }
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
}
