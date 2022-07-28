<?php

namespace App\Repositories\Sdm;

use Carbon\Carbon;
use App\Models\CutiModel;
use App\Models\LemburModel;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\AbsensiModel;
use App\Models\PegawaiModel;
use App\Models\PerizinanModel;
use App\Models\PenggajianModel;
use App\Helpers\ResponseHelpers;
use Illuminate\Support\Facades\DB;
use App\Helpers\FormatRupiahHelpers;
use Exception;

class PenggajianRepository
{
    public function getDataPenggajian()
    {
        try {
            $data = PenggajianModel::query()
                ->with('pegawai')
                ->get();

            return ResponseHelpers::ResponseSuccess(
                200,
                'Success get gaji user ',
                $data
            );
        } catch (Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
    public function getUserGaji()
    {
        try {
            $data = PegawaiModel::query()
                ->whereNull('deleted_at')
                ->where('active_account', '=', 1)
                ->get();
            return ResponseHelpers::ResponseSuccess(
                200,
                'Success get generate gaji user ',
                $data
            );
        } catch (Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }

    public function getPostGajiUser($params)
    {
        try {
            $dayActive = Carbon::now()
                ->startOfMonth()
                ->startOfDay()
                ->endOfDay()
                ->endOfMonth();
            $dayFirst = Carbon::now()
                ->startOfMonth()
                ->startOfDay()
                ->format('Y-m-d');
            $dayEnd = Carbon::now()
                ->endOfMonth()
                ->endOfDay()
                ->subDays(5)
                ->format('Y-m-d');

            $pegawaiId = isset($params['users_id']) ? $params['users_id'] : '';
            if (strlen($pegawaiId) == 0) {
                return ResponseHelpers::ResponseError(
                    'Pegawai is required',
                    400
                );
            }

            $pegawai = PegawaiModel::query()
                ->whereNull('deleted_at')
                ->where('active_account', '=', 1)
                ->where('pegawai_id', '=', $pegawaiId)
                ->first();

            $lemburs = LemburModel::query()
                ->whereNull('deleted_at')
                ->where('users_id', '=', $pegawai->pegawai_id)
                ->where('verification_status', '=', 1)
                ->whereBetween('date', [$dayFirst, $dayEnd])
                ->select(DB::raw('SUM(lembur.total_lembur) as total_lembur'))
                ->groupBy('total_lembur')
                ->first();

            $perizinans = PerizinanModel::query()
                ->whereNull('deleted_at')
                ->where('users_id', '=', $pegawai->pegawai_id)
                ->where('verification_status', '=', 1)
                ->whereBetween('date', [$dayFirst, $dayEnd])
                ->select(
                    DB::raw('SUM(perizinan.total_perizinan) as total_perizinan')
                )
                ->groupBy('total_perizinan')
                ->first();
            $perizinans = PerizinanModel::query()
                ->whereNull('deleted_at')
                ->where('users_id', '=', $pegawai->pegawai_id)
                ->where('verification_status', '=', 1)
                ->whereBetween('date', [$dayFirst, $dayEnd])
                ->select(
                    DB::raw('SUM(perizinan.total_perizinan) as total_perizinan')
                )
                ->first();
            $cutis = CutiModel::query()
                ->whereNull('deleted_at')
                ->where('users_id', '=', $pegawai->pegawai_id)
                ->where('verification_status', '=', 1)
                ->whereDate('date_in_cuti', '>=', $dayFirst)
                ->whereDate('date_in_cuti', '<=', $dayEnd)
                ->select(DB::raw('SUM(cuti.total_cuti) as total_cuti'))
                ->first();
            $absensi = AbsensiModel::query()
                ->whereNull('deleted_at')
                ->where('users_id', '=', $pegawai->pegawai_id)
                ->whereBetween('date', [$dayFirst, $dayEnd])
                ->where('verification_status', '=', 1)
                ->select(DB::raw('SUM(absensi.duration_time) as duration_time'))
                ->first();

            $data = new PenggajianModel();
            $data->penggajian_id = Str::uuid();
            $data->date_in_work = $dayFirst;
            $data->date_out_work = $dayEnd;
            $data->users_id = $pegawai->pegawai_id;
            $data->lembur_work = $lemburs->total_lembur ?? 0;
            $data->perizinan_work = $perizinans->total_perizinan ?? 0;
            $data->cuti_work = $cutis->total_cuti ?? 0;
            $data->absensi_work = $absensi->duration_time;
            $data->intenship_cuti_work = $cutis->total_cuti ? 0 : 500000;
            $data->gaji_pokok = $absensi->duration_time * 25000 ?? 0;
            $data->total_perizinan_pengurangan =
                $perizinans->total_perizinan * 25000 ?? 0;
            $data->total_gaji_pokok =
                $data->gaji_pokok +
                $data->intenship_cuti_work -
                $data->total_perizinan_pengurangan;
            $data->save();
            return ResponseHelpers::ResponseSuccess(
                200,
                'Success get generate gaji user ',
                $data
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
    // public function getDataUser()
    // {
    //     try {
    //         $dayActive = Carbon::now()
    //             ->startOfMonth()
    //             ->startOfDay()
    //             ->endOfDay()
    //             ->endOfMonth();
    //         $data = PegawaiModel::query()
    //             ->selectRaw('*, ROW_NUMBER() over(ORDER BY pegawai_id) nomor')
    //             ->where('active_account', '=', 1)
    //             ->select('pegawai_id', 'name', 'email')
    //             ->whereNull('deleted_at')
    //             ->get();

    //         $absensi = AbsensiModel::query()
    //             ->where('verification_status', '=', 1)
    //             ->where('created_at', '<>', $dayActive);

    //         $cuti = CutiModel::query()
    //             ->where('verification_status', '=', 1)
    //             ->where('created_at', '<>', $dayActive);

    //         $lembur = LemburModel::query()
    //             ->where('verification_status', '=', 1)
    //             ->where('created_at', '<>', $dayActive)
    //             ->select(DB::raw('SUM(lembur.total_lembur) as total_lembur'));

    //         $perizinan = PerizinanModel::query()
    //             ->where('verification_status', '=', 1)
    //             ->where('created_at', '<>', $dayActive)
    //             ->select(
    //                 DB::raw('SUM(perizinan.total_perizinan) as total_perizinan')
    //             );

    //         $lemburGaji = 50000;
    //         $perhitunganUang = 25000;

    //         $array = [];
    //         foreach ($data as $key => $value) {
    //             $absensi = $absensi
    //                 ->where('users_id', '=', $value->pegawai_id)
    //                 ->select(
    //                     DB::raw('SUM(absensi.duration_time) as duration_time')
    //                 )
    //                 ->first();
    //             $cuti = $cuti
    //                 ->where('users_id', '=', $value->pegawai_id)
    //                 ->select(DB::raw('SUM(cuti.total_cuti) as total_cuti'))
    //                 ->first();
    //             $lembur = $lembur
    //                 // ->where('users_id', '=', $value->pegawai_id)
    //                 ->select('total_lembur')
    //                 ->first();
    //             $perizinan = $perizinan
    //                 // ->where('users_id', '=', $value->pegawai_id)
    //                 ->first();

    //             array_push($array, [
    //                 'name' => $value->name,
    //                 'email' => $value->email,
    //                 'lemburan' => $lembur->total_lembur,
    //                 'perizinan' => $perizinan->total_perizinan,
    //                 'absensi' => $absensi->duration_time,
    //                 'cuti' => $cuti->total_cuti ? $cuti->total_cuti : 0,
    //                 'total_cuti_perizinan' =>
    //                     $perizinan->total_perizinan + $cuti->total_cuti,
    //                 'total_absensi_perjam' => $absensi->duration_time * 25000,
    //                 'total_cuti_intenship' => $cuti->total_cuti
    //                     ? $cuti->total_cuti
    //                     : 500000,
    //             ]);
    //         }

    //         return ResponseHelpers::ResponseSuccess(
    //             200,
    //             'Success get penggajian user ',
    //             $array
    //         );
    //     } catch (\Exception $e) {
    //         return ResponseHelpers::ResponseError($e->getMessage(), 400);
    //     }
    // }
}
