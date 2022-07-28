<?php

namespace App\Repositories\Admin;

use Carbon\Carbon;
use App\Models\User;
use Namshi\JOSE\JWT;
use Illuminate\Support\Str;
use App\Helpers\ResponseHelpers;
use Illuminate\Support\Facades\Auth;

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

            $admin = User::where('email', $email)->first();

            if (!$admin) {
                return ResponseHelpers::ResponseError('Email not found', 400);
            }
            if (
                !User::where([
                    'email' => $email,
                    'password' => $password,
                ])
            ) {
                return ResponseHelpers::ResponseError(
                    'Invalid email or password',
                    400
                );
            }

            $credentials = $params->only(['email', 'password']);
            if (
                !($token = auth()
                    ->guard('api_admin')
                    ->attempt($credentials))
            ) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            return response()->json(
                [
                    'success' => true,
                    'user' => auth()
                        ->guard('api_admin')
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
                        ->guard('api_admin')
                        ->user(),
                ],
                200
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->user());
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
