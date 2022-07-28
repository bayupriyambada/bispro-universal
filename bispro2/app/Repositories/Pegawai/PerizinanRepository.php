<?php

namespace App\Repositories\Pegawai;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\AbsensiModel;
use App\Models\PerizinanModel;
use App\Helpers\ResponseHelpers;
use App\Helpers\IndonesiaTimeHelpers;

class PerizinanRepository
{
    public function getCariData($params)
    {
        try {
            $data = PerizinanModel::query()->whereNull('deleted_at');
            $cari = isset($params['cari']) ? $params['cari'] : '';
            if (strlen($cari) > 0) {
                $data->where(function ($query) use ($cari) {
                    $query->where(
                        'date_in_perizinan',
                        'LIKE',
                        '%' . $cari . '%'
                    );
                    $query->orWhere(
                        'date_out_perizinan',
                        'LIKE',
                        '%' . $cari . '%'
                    );
                });
            }
            $data = $data
                ->selectRaw('*, ROW_NUMBER() over(ORDER BY perizinan_id) nomor')
                ->get();
            return ResponseHelpers::ResponseSuccessFilter(
                200,
                'Success find data ' . $data->count() . ' data.',
                $data
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
    public function getListData()
    {
        try {
            $date = Carbon::now()
                ->startOfMonth()
                ->startOfDay()
                ->endOfDay()
                ->endOfMonth();
            $data = PerizinanModel::query()
                ->selectRaw('*, ROW_NUMBER() over(ORDER BY perizinan_id) nomor')
                ->where(
                    'users_id',
                    auth()
                        ->guard('api_pegawai')
                        ->user()->pegawai_id
                )
                ->where('created_at', '<>', $date)
                ->whereNull('deleted_at')
                ->get();

            return ResponseHelpers::ResponseSuccess(
                200,
                'Success get data ' .
                    auth()
                        ->guard('api_pegawai')
                        ->user()->name,
                $data
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }

    public function getPostData($params)
    {
        try {
            $perizinanDescription = isset($params['description_perizinan'])
                ? $params['description_perizinan']
                : '';
            if (strlen($perizinanDescription) == 0) {
                return ResponseHelpers::ResponseError(
                    'Perizinan description is required',
                    400
                );
            }
            $timeIn = isset($params['time_in_perizinan'])
                ? $params['time_in_perizinan']
                : '';
            if (strlen($timeIn) == 0) {
                return ResponseHelpers::ResponseError(
                    'Time in perizinan is required',
                    400
                );
            }
            $timeOut = isset($params['time_out_perizinan'])
                ? $params['time_out_perizinan']
                : '';
            if (strlen($timeOut) == 0) {
                return ResponseHelpers::ResponseError(
                    'Date out perizinan is required',
                    400
                );
            }

            $dateToday = IndonesiaTimeHelpers::getDateIndonesia(Carbon::now());

            $absensiPegawai = AbsensiModel::query()
                ->whereDate('date', '=', $dateToday)
                ->first();
            $perizinanId = isset($params['perizinan_id'])
                ? $params['perizinan_id']
                : '';
            if (strlen($perizinanId) == 0) {
                $data = new PerizinanModel();
                $data->users_id = auth()
                    ->guard('api_pegawai')
                    ->user()->pegawai_id;
                $data->created_at = IndonesiaTimeHelpers::getIndonesiaTime(
                    Carbon::now()
                );
            } else {
                $data = PerizinanModel::query()->find($perizinanId);
                if (is_null($data)) {
                    return ResponseHelpers::ResponseError(
                        'Data not found',
                        400
                    );
                }
                $data->updated_at = IndonesiaTimeHelpers::getIndonesiaTime(
                    Carbon::now()
                );
                $data->users_id = auth()
                    ->guard('api_pegawai')
                    ->user()->pegawai_id;
                if (!is_null($data->deleted_at)) {
                    return ResponseHelpers::ResponseError(
                        'Data already deleted',
                        400
                    );
                }
            }

            $data->description_perizinan = $perizinanDescription;
            $data->time_in_perizinan = $timeIn;
            $data->time_out_perizinan = $timeOut;
            $data->date = $dateToday;

            $data->total_perizinan = Carbon::parse(
                $data->time_out_perizinan
            )->diffInHours(Carbon::parse($data->time_in_perizinan));

            $dateInDay = PerizinanModel::query()
                ->whereDate('date', '=', $data->date)
                ->whereNull('deleted_at')
                ->first();

            if (!is_null($dateInDay)) {
                return ResponseHelpers::ResponseError(
                    'Time in perizinan already exist',
                    400
                );
            }
            if ($data->time_out_perizinan < $data->time_in_perizinan) {
                return ResponseHelpers::ResponseError(
                    'Time out perizinan must be greater than date in perizinan',
                    400
                );
            } elseif ($data->date < $absensiPegawai->date) {
                return 'tidak boleh lebih kecil dari tanggal absensi';
            } else {
                $data->save();
                return ResponseHelpers::ResponseSuccess(
                    200,
                    'Success save data',
                    $data
                );
            }
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }

    public function getDelete($params)
    {
        try {
            $perizinanId = isset($params['perizinan_id'])
                ? $params['perizinan_id']
                : '';
            if (strlen($perizinanId) == 0) {
                return ResponseHelpers::ResponseError(
                    'Cuti id is required',
                    400
                );
            }
            $data = PerizinanModel::query()->find($perizinanId);
            if (is_null($data)) {
                return ResponseHelpers::ResponseError('Data not found', 400);
            }

            if (!is_null($data->deleted_at)) {
                return ResponseHelpers::ResponseError(
                    'Data already deleted',
                    400
                );
            }

            $data->deleted_at = IndonesiaTimeHelpers::getIndonesiaTime(
                Carbon::now()
            );

            if ($data->verification_status == 1) {
                return ResponseHelpers::ResponseError(
                    'Data already verified',
                    400
                );
            } else {
                $data->save();
                return ResponseHelpers::ResponseSuccess(
                    200,
                    'Success delete data ',
                    $data
                );
            }
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
}
