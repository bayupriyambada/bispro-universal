<?php

namespace App\Repositories\Pegawai;

use Carbon\Carbon;
use App\Models\LemburModel;
use Illuminate\Support\Str;
use App\Helpers\ResponseHelpers;
use App\Helpers\IndonesiaTimeHelpers;

class LemburRepository
{
    public function getCariData($params)
    {
        try {
            $data = LemburModel::query();
            $cari = isset($params['cari']) ? $params['cari'] : '';
            if (strlen($cari) > 0) {
                $data->where(function ($query) use ($cari) {
                    $query->where('date', 'LIKE', '%' . $cari . '%');
                });
            }
            $data = $data
                ->selectRaw('*, ROW_NUMBER() over(ORDER BY lembur_id) nomor')
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

            $data = LemburModel::query()
                ->selectRaw('*, ROW_NUMBER() over(ORDER BY lembur_id) nomor')
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
            $timeIn = isset($params['time_in']) ? $params['time_in'] : '';
            if (strlen($timeIn) == 0) {
                return ResponseHelpers::ResponseError(
                    'Time in lembur is required',
                    400
                );
            }
            $timeOut = isset($params['time_out']) ? $params['time_out'] : '';
            if (strlen($timeOut) == 0) {
                return ResponseHelpers::ResponseError(
                    'Time out lembur is required',
                    400
                );
            }

            $description = isset($params['description_work'])
                ? $params['description_work']
                : '';

            if (strlen($description) == 0) {
                return ResponseHelpers::ResponseError(
                    'Description lembur is required',
                    400
                );
            }
            $dateToday = IndonesiaTimeHelpers::getDateIndonesia(Carbon::now());
            // $absensiPegawai = LemburModel::query()
            //     ->whereDate('date', '=', $dateToday)
            //     ->first();
            $lemburId = isset($params['lembur_id']) ? $params['lembur_id'] : '';
            if (strlen($lemburId) == 0) {
                $data = new LemburModel();
                $data->lembur_id = Str::uuid();
                $data->users_id = auth()
                    ->guard('api_pegawai')
                    ->user()->pegawai_id;
                $data->created_at = IndonesiaTimeHelpers::getIndonesiaTime(
                    Carbon::now()
                );
            } else {
                $data = LemburModel::query()->find($lemburId);
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

            $data->description_work = $description;
            $data->date = $dateToday;
            $data->time_in = $timeIn;
            $data->time_out = $timeOut;
            $data->total_lembur = Carbon::parse($data->time_out)->diffInHours(
                Carbon::parse($data->time_in)
            );
            $TimeCuti = LemburModel::query()
                ->whereDate('date', '=', $dateToday)
                ->whereNull('deleted_at')
                ->first();

            if (!is_null($TimeCuti)) {
                return ResponseHelpers::ResponseError(
                    'Time lembur already exist today on ' .
                        $data->total_lembur .
                        ' minutes',
                    400
                );
            }
            if ($data->time_out < $data->time_in) {
                return ResponseHelpers::ResponseError(
                    'Time out lembur must be greater than time in lembur',
                    400
                );
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
            $lemburId = isset($params['lembur_id']) ? $params['lembur_id'] : '';
            if (strlen($lemburId) == 0) {
                return ResponseHelpers::ResponseError(
                    'Cuti id is required',
                    400
                );
            }
            $data = LemburModel::query()->find($lemburId);
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
                    $data->name
                );
            }
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
}
