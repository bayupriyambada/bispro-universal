<?php

namespace App\Http\Controllers\Sdm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Sdm\AuthRepository;

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

    public function getPersonalMe()
    {
        $data = (new AuthRepository())->personalMe();
        return $data;
    }
    // public function getUpdatePersonal(Request $request, $sdmId)
    // {
    //     $data = [
    //         'sdm_id' => $sdmId,
    //     ];
    //     $data = (new AuthRepository())->updatePersonal($$da);
    //     return $data;
    // }

    public function getLogout()
    {
        $data = (new AuthRepository())->logout();
        return $data;
    }
}
