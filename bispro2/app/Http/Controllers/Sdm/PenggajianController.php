<?php

namespace App\Http\Controllers\Sdm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Sdm\PenggajianRepository;

class PenggajianController extends Controller
{
    public function getDataPegawai()
    {
        $data = (new PenggajianRepository())->getUserGaji();
        return $data;
    }
    public function getDataGajiUser()
    {
        $data = (new PenggajianRepository())->getDataPenggajian();
        return $data;
    }

    public function getPenggajianUser(Request $request)
    {
        $data = [
            'users_id' => $request->input('users_id'),
        ];

        $data = (new PenggajianRepository())->getPostGajiUser($data);
        return $data;
    }

    // public function getSearchGajiUser(Request $request)
    // {
    //     $data = [
    //         'cari' => $request->input('cari'),
    //     ];
    //     $data = (new PenggajianRepository())->getCariGaji($data);
    //     return $data;
    // }
}
