<?php

namespace App\Repositories\Admin;

use Carbon\Carbon;
use App\Models\SdmModel;
use App\Helpers\ResponseHelpers;
use Illuminate\Support\Facades\Hash;
use App\Helpers\IndonesiaTimeHelpers;

class SdmRepository
{
    public function getCariData($params)
    {
        try {
            $data = SdmModel::query()->whereNull('deleted_at');
            $cari = isset($params['cari']) ? $params['cari'] : '';
            if (strlen($cari) > 0) {
                $data->where(function ($query) use ($cari) {
                    $query->whereRaw(
                        "lower(name) LIKE '%" . strtolower($cari) . "%'"
                    );
                });
            }
            $data = $data
                ->selectRaw('*, ROW_NUMBER() over(ORDER BY sdm_id) nomor')
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
            $data = SdmModel::query()
                ->selectRaw('*, ROW_NUMBER() over(ORDER BY sdm_id) nomor')
                ->whereNull('deleted_at')
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
                $data = SdmModel::query()
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
            $sdmId = isset($params['sdm_id']) ? $params['sdm_id'] : '';
            if (strlen($sdmId) == 0) {
                $data = new SdmModel();
                $data->created_at = IndonesiaTimeHelpers::getIndonesiaTime(
                    Carbon::now()
                );
            } else {
                $data = SdmModel::query()->find($sdmId);
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
            $sdmId = isset($params['sdm_id']) ? $params['sdm_id'] : '';
            if ($sdmId) {
                $data = SdmModel::find($sdmId);
                $data->active_account = 1;
                $data->active_at = IndonesiaTimeHelpers::getIndonesiaTime(
                    Carbon::now()
                );
            }

            $data->save();
            return ResponseHelpers::ResponseSuccess(
                'Success activation account sdm data',
                200,
                $data->name . ' has been activated'
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
    public function getDelete($params)
    {
        try {
            $sdmId = isset($params['sdm_id']) ? $params['sdm_id'] : '';
            if (strlen($sdmId) == 0) {
                return ResponseHelpers::ResponseError(
                    'Sdm Id is required',
                    400
                );
            }
            $data = SdmModel::query()->find($sdmId);
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
