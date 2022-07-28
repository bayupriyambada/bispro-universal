<?php

namespace App\Repositories\Admin;

use Carbon\Carbon;
use App\Helpers\ResponseHelpers;
use App\Helpers\IndonesiaTimeHelpers;
use App\Models\Admin\DepartemenModel;

class DepartemenRepository
{
    public function getCariData($params)
    {
        try {
            $data = DepartemenModel::query()->whereNull('deleted_at');
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
                    '*, ROW_NUMBER() over(ORDER BY departemen_id) nomor'
                )
                ->with(['getUnitKerja:unit_kerja_id,name'])
                ->paginate(10)
                ->makeHidden(['created_at', 'updated_at', 'deleted_at']);
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
            $data = DepartemenModel::query()
                ->selectRaw(
                    '*, ROW_NUMBER() over(ORDER BY departemen_id) nomor'
                )
                ->whereNull('deleted_at')
                ->with('getUnitKerja')
                ->get();
            return ResponseHelpers::ResponseSuccess(
                200,
                'Success get data departemen',
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

            $unitKerjaId = isset($params['unit_kerja_id'])
                ? $params['unit_kerja_id']
                : '';

            if (strlen($unitKerjaId) == 0) {
                return ResponseHelpers::ResponseError(
                    'Unit Kerja is required',
                    400
                );
            }

            $departemenId = isset($params['departemen_id'])
                ? $params['departemen_id']
                : '';
            if (strlen($departemenId) == 0) {
                $data = new DepartemenModel();
                $data->created_at = IndonesiaTimeHelpers::getIndonesiaTime(
                    Carbon::now()
                );
            } else {
                $data = DepartemenModel::query()->find($departemenId);
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
            $data->unit_kerja_id = $unitKerjaId;
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
            $departemenId = isset($params['departemen_id'])
                ? $params['departemen_id']
                : '';
            if (strlen($departemenId) == 0) {
                return ResponseHelpers::ResponseError(
                    'Departemen id is required',
                    400
                );
            }
            $data = DepartemenModel::query()->find($departemenId);
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
