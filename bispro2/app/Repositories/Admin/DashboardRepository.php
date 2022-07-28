<?php

namespace App\Repositories\Admin;

use App\Helpers\ResponseHelpers;
use App\Models\AbsensiModel;
use App\Models\Admin\DepartemenModel;
use App\Models\Admin\JenisCutiModel;
use App\Models\Admin\UnitKerjaModel;
use App\Models\CutiModel;
use App\Models\LemburModel;
use App\Models\PegawaiModel;
use App\Models\PenggajianModel;
use App\Models\PerizinanModel;
use App\Models\SdmModel;

class DashboardRepository
{
    public function getDataPersonal()
    {
        try {
            return ResponseHelpers::ResponseSuccess(
                'Success',
                200,
                auth()
                    ->guard('api_admin')
                    ->user()
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }

    public function getCountJenisCuti()
    {
        try {
            $data = JenisCutiModel::query()
                ->whereNull('deleted_at')
                ->count();
            return response()->json([
                'success' => 200,
                'messages' => 'Get Count Data',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
    public function getCountCuti()
    {
        try {
            $data = CutiModel::query()
                ->whereNull('deleted_at')
                ->count();
            return response()->json([
                'success' => 200,
                'messages' => 'Get Count Data',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
    public function getCountUnitKerja()
    {
        try {
            $data = UnitKerjaModel::query()
                ->whereNull('deleted_at')
                ->count();
            return response()->json([
                'success' => 200,
                'messages' => 'Get Count Data',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
    public function getCountDepartemen()
    {
        try {
            $data = DepartemenModel::query()
                ->whereNull('deleted_at')
                ->count();
            return response()->json([
                'success' => 200,
                'messages' => 'Get Count Data',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
    public function getCountLembur()
    {
        try {
            $data = LemburModel::query()
                ->whereNull('deleted_at')
                ->count();
            return response()->json([
                'success' => 200,
                'messages' => 'Get Count Data',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
    public function getCountPerizinan()
    {
        try {
            $data = PerizinanModel::query()
                ->whereNull('deleted_at')
                ->count();
            return response()->json([
                'success' => 200,
                'messages' => 'Get Count Data',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
    public function getCountAbsensi()
    {
        try {
            $data = AbsensiModel::query()
                ->whereNull('deleted_at')
                ->count();
            return response()->json([
                'success' => 200,
                'messages' => 'Get Count Data',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
    public function getCountSdm()
    {
        try {
            $data = SdmModel::query()
                ->whereNull('deleted_at')
                ->count();
            return response()->json([
                'success' => 200,
                'messages' => 'Get Count Data',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
    public function getCountPegawai()
    {
        try {
            $data = PegawaiModel::query()
                ->whereNull('deleted_at')
                ->count();
            return response()->json([
                'success' => 200,
                'messages' => 'Get Count Data',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
    public function getCountGaji()
    {
        try {
            $data = PenggajianModel::query()->count();
            return response()->json([
                'success' => 200,
                'messages' => 'Get Count Data',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
}
