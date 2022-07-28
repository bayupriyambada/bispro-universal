<?php

namespace App\Repositories\Pegawai;

use App\Models\PegawaiModel;
use App\Helpers\ResponseHelpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

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

            $pegawai = PegawaiModel::where('email', $email)->first();

            if (!$pegawai) {
                return ResponseHelpers::ResponseError('Email not found', 400);
            }
            if (
                !PegawaiModel::where([
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

            if (!$pegawai->active_account == 1) {
                return ResponseHelpers::ResponseError(
                    'Your account is not active',
                    400
                );
            }
            $credentials = $params->only(['email', 'password']);
            if (
                !($token = auth()
                    ->guard('api_pegawai')
                    ->attempt($credentials))
            ) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            return response()->json(
                [
                    'success' => true,
                    'user' => auth()
                        ->guard('api_pegawai')
                        ->user(),
                    'token' => $token,
                ],
                200
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 500);
        }
    }

    public function refreshToken($params)
    {
        //refresh "token"
        $refreshToken = JWTAuth::refresh(JWTAuth::getToken());

        //set user dengan "token" baru
        $user = JWTAuth::setToken($refreshToken)->toUser();

        //set header "Authorization" dengan type Bearer + "token" baru
        $params->headers->set('Authorization', 'Bearer ' . $refreshToken);

        //response data "user" dengan "token" baru
        return response()->json(
            [
                'success' => true,
                'data' => $user,
                'token' => $refreshToken,
            ],
            200
        );
    }

    public function personalMe()
    {
        try {
            return response()->json(
                [
                    'success' => true,
                    'user' => auth()
                        ->guard('api_pegawai')
                        ->user(),
                ],
                200
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
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
                'user' => auth()->guard('api_pegawai'),
                'token' => $token,
                'expires_in' => 60 * 24 * 30,
            ],
            200,
            'Login success'
        );
    }
}
