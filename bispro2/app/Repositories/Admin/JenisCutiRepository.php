<?php

namespace App\Repositories\Admin;

use Carbon\Carbon;
use App\Helpers\ResponseHelpers;
use App\Models\Admin\JenisCutiModel;
use App\Helpers\IndonesiaTimeHelpers;
use App\Models\CutiModel;

class JenisCutiRepository
{
    public function getCariData($params)
    {
        try {
            $data = JenisCutiModel::query();
            $cari = isset($params['cari']) ? $params['cari'] : '';
            if (strlen($cari) > 0) {
                $data->where(function ($query) use ($cari) {
                    $query->whereRaw(
                        "lower(name) LIKE '%" . strtolower($cari) . "%'"
                    );
                });
            }
            $data = $data
                ->selectRaw(
                    '*, ROW_NUMBER() over(ORDER BY jenis_cuti_id) nomor'
                )
                ->get();
            if ($data) {
                return ResponseHelpers::ResponseSuccessFilter(
                    200,
                    'Success find data ' . $data->count() . ' data.',
                    $data
                );
            } else {
                return ResponseHelpers::ResponseError(404, 'Data not found');
            }
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
    public function getData()
    {
        try {
            $data = JenisCutiModel::query()
                ->selectRaw(
                    '*, ROW_NUMBER() over(ORDER BY jenis_cuti_id) nomor'
                )
                ->whereNull('deleted_at')
                ->withCount('cuti')
                ->get();
            return ResponseHelpers::ResponseSuccess(
                200,
                'Success get data',
                $data
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }

    public function getPost($params)
    {
        try {
            $name = isset($params['name']) ? $params['name'] : '';
            if (strlen($name) == 0) {
                return ResponseHelpers::ResponseError('Name is required', 400);
            }

            $jenisCutiId = isset($params['jenis_cuti_id'])
                ? $params['jenis_cuti_id']
                : '';
            if (strlen($jenisCutiId) == 0) {
                $data = new JenisCutiModel();
                $data->created_at = IndonesiaTimeHelpers::getIndonesiaTime(
                    Carbon::now()
                );
            } else {
                $data = JenisCutiModel::query()->find($jenisCutiId);
                $data->updated_at = IndonesiaTimeHelpers::getIndonesiaTime(
                    Carbon::now()
                );

                if (is_null($data)) {
                    return ResponseHelpers::ResponseError(
                        'Data not found',
                        400
                    );
                }
                if (!is_null($data->deleted_at)) {
                    return ResponseHelpers::ResponseError(
                        'Data already deleted',
                        400
                    );
                }
            }
            $data->name = $name;
            $data->save();

            return ResponseHelpers::ResponseSuccess(
                'Success release data',
                200,
                $data
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
    public function getDelete($params)
    {
        try {
            $jenisCutiId = isset($params['jenis_cuti_id'])
                ? $params['jenis_cuti_id']
                : '';
            if (strlen($jenisCutiId) == 0) {
                return ResponseHelpers::ResponseError(
                    'Jenis Cuti is required',
                    400
                );
            }
            $data = JenisCutiModel::query()->find($jenisCutiId);
            if (is_null($data)) {
                return ResponseHelpers::ResponseError('Data not found', 400);
            }
            if (!is_null($data->deleted_at)) {
                return ResponseHelpers::ResponseError(
                    'Data already deleted',
                    400
                );
            }

            // check data having relationship
            $cuti = CutiModel::query()
                ->where('jenis_cuti_id', '=', $data->jenis_cuti_id)
                ->first();
            if (!is_null($cuti)) {
                return ResponseHelpers::ResponseError(
                    'Jenis Cuti have relationship in Departemen',
                    400
                );
            }

            $data->deleted_at = IndonesiaTimeHelpers::getIndonesiaTime(
                Carbon::now()
            );

            $data->save();

            return ResponseHelpers::ResponseSuccess(
                200,
                'Success delete data ',
                $data->name
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
}
