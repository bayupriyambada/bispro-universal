<?php

namespace App\Repositories\Sdm;

use App\Models\SdmModel;
use App\Helpers\ResponseHelpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    public function login($params)
    {
        try {
            $email = isset($params['email']) ? $params['email'] : '';
            if (strlen($email) == 0) {
                return ResponseHelpers::ResponseError('Email is required', 400);
            }
            $password = isset($params['password']) ? $params['password'] : '';
            if (strlen($password) == 0) {
                return ResponseHelpers::ResponseError(
                    'Password is required',
                    400
                );
            }

            $sdm = SdmModel::where('email', $email)->first();
            if (!$sdm) {
                return ResponseHelpers::ResponseError('Email not found', 400);
            }
            if (
                !SdmModel::where([
                    'email' => $email,
                    'password' => $password,
                    'active_account' => 1,
                ])
            ) {
                return ResponseHelpers::ResponseError(
                    'Invalid email or password',
                    400
                );
            }

            if (!$sdm->active_account == 1) {
                return ResponseHelpers::ResponseError(
                    'Your account is not active',
                    400
                );
            }
            $credentials = $params->only(['email', 'password']);
            if (
                !($token = auth()
                    ->guard('api_sdm')
                    ->attempt($credentials))
            ) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return response()->json(
                [
                    'success' => true,
                    'user' => auth()
                        ->guard('api_sdm')
                        ->user(),
                    'token' => $token,
                ],
                200
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 500);
        }
    }

    public function personalMe()
    {
        try {
            return response()->json(
                [
                    'success' => true,
                    'user' => auth()
                        ->guard('api_sdm')
                        ->user(),
                ],
                200
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }

    // public function updatePersonal($params)
    // {
    //     try {
    //         $sdmId = isset($params['sdm_id']) ? $params['sdm_id'] : '';
    //         if (strlen($sdmId) == 0) {
    //             return ResponseHelpers::ResponseError(
    //                 'Sdm Id is required',
    //                 400
    //             );
    //         }
    //         $name = isset($params['name']) ? $params['name'] : '';
    //         $phone = isset($params['phone']) ? $params['phone'] : '';
    //         $email = isset($params['email']) ? $params['email'] : '';
    //         $address = isset($params['address']) ? $params['address'] : '';
    //         $date_birth = isset($params['date_birth'])
    //             ? $params['date_birth']
    //             : '';

    //         $sdm = SdmModel::find($sdmId);
    //         if (!$sdm) {
    //             return ResponseHelpers::ResponseError('Sdm not found', 400);
    //         }

    //         $sdm->name = $name;
    //         $sdm->email = $email;
    //         $sdm->phone = $phone;
    //         $sdm->address = $address;
    //         $sdm->date_birth = $date_birth;
    //         $password = $sdm->password ? $sdm->password : '';
    //         $sdm->password = Hash::make($password);

    //         $sdm->save();
    //         $this->refresh();
    //         return ResponseHelpers::ResponseSuccess(
    //             200,
    //             'Personal Data Updated',
    //             $sdm
    //         );
    //     } catch (\Exception $e) {
    //         return ResponseHelpers::ResponseError($e->getMessage(), 400);
    //     }
    // }

    private function refresh()
    {
        return $this->respondWithToken(auth()->guard('api_sdm'));
    }

    public function logout()
    {
        Auth::logout();
        return ResponseHelpers::ResponseSuccess(200, 'Logout success', []);
    }

    protected function respondWithToken($token)
    {
        return ResponseHelpers::ResponseSuccess(
            [
                'user' => auth()->user(),
                'token' => $token,
                'expires_in' => 60 * 24 * 30,
            ],
            200,
            'Login success'
        );
    }
}
