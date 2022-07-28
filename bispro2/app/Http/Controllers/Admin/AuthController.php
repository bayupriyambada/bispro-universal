<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\AuthRepository;

class AuthController extends Controller
{
    public function getLogin(Request $request)
    {
        $data = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
        $data = (new AuthRepository())->login($request);
        return $data;
    }

    public function getRefreshToken()
    {
        $data = (new AuthRepository())->refresh();
        return $data;
    }

    public function getPersonalMe()
    {
        $data = (new AuthRepository())->personalMe();
        return $data;
    }

    public function getLogout()
    {
        $data = (new AuthRepository())->logout();
        return $data;
    }
}
