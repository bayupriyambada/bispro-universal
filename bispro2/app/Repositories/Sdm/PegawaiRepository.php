<?php

namespace App\Repositories\Sdm;

use Carbon\Carbon;
use App\Models\PegawaiModel;
use App\Helpers\ResponseHelpers;
use Illuminate\Support\Facades\Hash;
use App\Helpers\IndonesiaTimeHelpers;

class PegawaiRepository
{
    public function getCariData($params)
    {
        try {
            $data = PegawaiModel::query()->whereNull('deleted_at');
            $cari = isset($params['cari']) ? $params['cari'] : '';
            if (strlen($cari) > 0) {
                $data->where(function ($query) use ($cari) {
                    $query->whereRaw(
                        "lower(name) LIKE '%" . strtolower($cari) . "%'"
                    );
                    $query->whereRaw(
                        "lower(email) LIKE '%" . strtolower($cari) . "%'"
                    );
                });
            }
            $data = $data
                ->selectRaw('*, ROW_NUMBER() over(ORDER BY pegawai_id) nomor')
                ->with('parent')
                ->get()
                ->makeHidden(['created_at', 'updated_at', 'deleted_at']);
            return ResponseHelpers::ResponseSuccessFilter(
                200,
                'Success find data ' . $data->count() . ' data.',
                $data
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
    public function getData()
    {
        try {
            $data = PegawaiModel::query()
                ->selectRaw('*, ROW_NUMBER() over(ORDER BY pegawai_id) nomor')
                ->whereNull('deleted_at')
                ->with('parent')
                ->get();
            return ResponseHelpers::ResponseSuccess(
                200,
                'Success get data user',
                $data
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }

    public function getAccountActive()
    {
        try {
            $data = PegawaiModel::query()
                ->selectRaw('*, ROW_NUMBER() over(ORDER BY pegawai_id) nomor')
                ->whereNull('deleted_at')
                ->where('active_account', '=', 1)
                ->with('parent')
                ->get();
            return ResponseHelpers::ResponseSuccess(
                200,
                'Success get data user',
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
            $email = isset($params['email']) ? $params['email'] : '';
            if (strlen($email) == 0) {
                return ResponseHelpers::ResponseError('Email is required', 400);
            }

            if (!is_null($email)) {
                $data = PegawaiModel::query()
                    ->where('email', $email)
                    ->first();
                if ($data) {
                    return ResponseHelpers::ResponseError(
                        'Email already exists',
                        400
                    );
                }
            }

            $password = isset($params['password']) ? $params['password'] : '';
            if (strlen($password) == 0) {
                return ResponseHelpers::ResponseError(
                    'Password is required',
                    400
                );
            }
            $phone = isset($params['phone']) ? $params['phone'] : '';
            $address = isset($params['address']) ? $params['address'] : '';
            $phone = isset($params['phone']) ? $params['phone'] : '';
            $dateBirth = isset($params['date_birth'])
                ? $params['date_birth']
                : '';
            $departemenId = isset($params['departemen_id'])
                ? $params['departemen_id']
                : '';
            if (strlen($departemenId) == 0) {
                return ResponseHelpers::ResponseError(
                    'Departemen is required',
                    400
                );
            }

            $pegawaiPositionId = isset($params['pegawai_position_id'])
                ? $params['pegawai_position_id']
                : '';

            $pegawaiId = isset($params['pegawai_id'])
                ? $params['pegawai_id']
                : '';
            if (strlen($pegawaiId) == 0) {
                $data = new PegawaiModel();
                $data->created_at = IndonesiaTimeHelpers::getIndonesiaTime(
                    Carbon::now()
                );
            } else {
                $data = PegawaiModel::query()->find($pegawaiId);
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
            if (strlen($pegawaiPositionId) > 0) {
                $data->pegawai_position_id = $pegawaiPositionId;
            } else {
                $data->pegawai_position_id = null;
            }
            $data->email = $email;
            $data->password = Hash::make($password);
            $data->phone = $phone;
            $data->address = $address;
            $data->date_birth = $dateBirth;
            $data->departemen_id = $departemenId;
            $data->active_account = 0;
            $data->save();

            return ResponseHelpers::ResponseSuccess(
                200,
                'Success release account',
                $data
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }

    public function getVerificationAccount($params)
    {
        try {
            $pegawaiId = isset($params['pegawai_id'])
                ? $params['pegawai_id']
                : '';
            if ($pegawaiId) {
                $data = PegawaiModel::find($pegawaiId);
                $data->active_account = 1;
                $data->active_at = IndonesiaTimeHelpers::getIndonesiaTime(
                    Carbon::now()
                );
            }

            $data->save();
            return ResponseHelpers::ResponseSuccess(
                200,
                'Success activation account pegawai data',
                $data->name . ' has been activated'
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
    public function getDelete($params)
    {
        try {
            $pegawaiId = isset($params['pegawai_id'])
                ? $params['pegawai_id']
                : '';
            if (strlen($pegawaiId) == 0) {
                return ResponseHelpers::ResponseError(
                    'Sdm Id is required',
                    400
                );
            }
            $data = PegawaiModel::query()->find($pegawaiId);
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
