<?php

namespace App\Repositories\Pegawai;

use Carbon\Carbon;
use App\Models\CutiModel;
use Illuminate\Support\Str;
use App\Models\AbsensiModel;
use App\Helpers\ResponseHelpers;
use App\Models\Admin\JenisCutiModel;
use App\Helpers\IndonesiaTimeHelpers;

class CutiRepository
{
    public function getCariData($params)
    {
        try {
            $data = CutiModel::query()->whereNull('deleted_at');
            $cari = isset($params['cari']) ? $params['cari'] : '';
            if (strlen($cari) > 0) {
                $data->where(function ($query) use ($cari) {
                    $query->where('date_in_cuti', 'LIKE', '%' . $cari . '%');
                    $query->orWhere('date_out_cuti', 'LIKE', '%' . $cari . '%');
                });
            }
            $data = $data
                ->selectRaw('*, ROW_NUMBER() over(ORDER BY cuti_id) nomor')
                ->with(['jenisCuti'])
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
            $data = CutiModel::query()
                ->selectRaw('*, ROW_NUMBER() over(ORDER BY cuti_id) nomor')
                ->where('created_at', '<>', $date)
                ->whereNull('deleted_at')
                ->with(['jenisCuti'])
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
            $typeCuti = isset($params['jenis_cuti_id'])
                ? $params['jenis_cuti_id']
                : '';
            if (strlen($typeCuti) == 0) {
                return ResponseHelpers::ResponseError(
                    'Type cuti is required',
                    400
                );
            }
            $dateIn = isset($params['date_in_cuti'])
                ? $params['date_in_cuti']
                : '';
            if (strlen($dateIn) == 0) {
                return ResponseHelpers::ResponseError(
                    'Date in cuti is required',
                    400
                );
            }
            $dateOut = isset($params['date_out_cuti'])
                ? $params['date_out_cuti']
                : '';
            if (strlen($dateOut) == 0) {
                return ResponseHelpers::ResponseError(
                    'Date out cuti is required',
                    400
                );
            }

            $description = isset($params['description_cuti'])
                ? $params['description_cuti']
                : '';

            if (strlen($description) == 0) {
                return ResponseHelpers::ResponseError(
                    'Description cuti is required',
                    400
                );
            }
            $dateToday = IndonesiaTimeHelpers::getDateIndonesia(Carbon::now());

            $absensiPegawai = AbsensiModel::query()
                ->whereDate('date', '=', $dateToday)
                ->first();
            $cutiId = isset($params['cuti_id']) ? $params['cuti_id'] : '';
            if (strlen($cutiId) == 0) {
                $data = new CutiModel();
                $data->users_id = auth()
                    ->guard('api_pegawai')
                    ->user()->pegawai_id;
                $data->created_at = IndonesiaTimeHelpers::getIndonesiaTime(
                    Carbon::now()
                );
            } else {
                $data = CutiModel::query()->find($cutiId);
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

            $data->type_cuti = $typeCuti;
            $data->date_in_cuti = IndonesiaTimeHelpers::getDateIndonesia(
                $dateIn
            );
            $data->date_out_cuti = IndonesiaTimeHelpers::getDateIndonesia(
                $dateOut
            );

            $data->total_cuti = Carbon::parse($data->date_out_cuti)->diffInDays(
                Carbon::parse($data->date_in_cuti)
            );
            $data->description_cuti = $description;

            $dateInDay = CutiModel::query()
                ->whereDate('date_in_cuti', '=', $data->date_in_cuti)
                ->whereNull('deleted_at')
                ->first();

            if (!is_null($dateInDay)) {
                return ResponseHelpers::ResponseError(
                    'Date in cuti already exist',
                    400
                );
            }
            if ($data->date_out_cuti < $data->date_in_cuti) {
                return ResponseHelpers::ResponseError(
                    'Date out cuti must be greater than date in cuti',
                    400
                );
            } elseif ($data->date_in_cuti < $absensiPegawai->date) {
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
            $cutiId = isset($params['cuti_id']) ? $params['cuti_id'] : '';
            if (strlen($cutiId) == 0) {
                return ResponseHelpers::ResponseError(
                    'Cuti id is required',
                    400
                );
            }
            $data = CutiModel::query()->find($cutiId);
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
