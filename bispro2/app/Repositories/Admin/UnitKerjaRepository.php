<?php

namespace App\Repositories\Admin;

use App\Helpers\IndonesiaTimeHelpers;
use Carbon\Carbon;
use App\Helpers\ResponseHelpers;
use App\Models\Admin\DepartemenModel;
use App\Models\Admin\UnitKerjaModel;

class UnitKerjaRepository
{
    public function getCariData($params)
    {
        try {
            $data = UnitKerjaModel::query()->whereNull('deleted_at');
            $cari = isset($params['cari']) ? $params['cari'] : '';
            if (strlen($cari) > 0) {
                $data->where(function ($query) use ($cari) {
                    $query->whereRaw(
                        "lower(name) LIKE '%" . strtolower($cari) . "%'"
                    );
                });
            }
            $data = $data
                ->selectRaw('*, ROW_NUMBER() over(ORDER BY unit_id) nomor')
                ->paginate(10);
            if ($data) {
                return ResponseHelpers::ResponseSuccessFilter(
                    200,
                    'Success find data ' . $data->count() . ' data',
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
            $data = UnitKerjaModel::query()
                ->selectRaw(
                    '*, ROW_NUMBER() over(ORDER BY unit_kerja_id) nomor'
                )
                ->whereNull('deleted_at')
                ->withCount('getDepartemen')
                ->get();
            return ResponseHelpers::ResponseSuccess(
                200,
                'Success get data kerja unit',
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
                $data = new UnitKerjaModel();
                $data->created_at = IndonesiaTimeHelpers::getIndonesiaTime(
                    Carbon::now()
                );
            } else {
                $data = UnitKerjaModel::query()->find($unitKerjaId);
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
            $unitKerjaId = isset($params['unit_kerja_id'])
                ? $params['unit_kerja_id']
                : '';
            if (strlen($unitKerjaId) == 0) {
                return ResponseHelpers::ResponseError(
                    'Unit Kerja Id is required',
                    400
                );
            }
            $data = UnitKerjaModel::query()->find($unitKerjaId);
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
            $departemen = DepartemenModel::query()
                ->where('unit_kerja_id', '=', $data->unit_kerja_id)
                ->first();
            if (!is_null($departemen)) {
                return ResponseHelpers::ResponseError(
                    'Unit Kerja have relation in Departemen',
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
