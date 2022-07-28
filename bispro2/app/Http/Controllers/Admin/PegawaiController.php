<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\PegawaiRepository;

class PegawaiController extends Controller
{
    public function getListData()
    {
        $data = (new PegawaiRepository())->getData();
        return $data;
    }

    public function getPostData(Request $request)
    {
        $data = [
            'pegawai_id' => $request->input('pegawai_id'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'pegawai_position_id' => $request->input('pegawai_position_id'),
            'password' => $request->input('password'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'date_birth' => $request->input('date_birth'),
        ];
        $data = (new PegawaiRepository())->getPost($request);
        return $data;
    }

    public function getDeleteData($departemenId)
    {
        $data = [
            'departemen_id' => $departemenId,
        ];
        $data = (new PegawaiRepository())->getDelete($data);
        return $data;
    }

    public function getSearchData(Request $request)
    {
        $data = [
            'cari' => $request->input('cari'),
        ];
        $data = (new PegawaiRepository())->getCariData($data);
        return $data;
    }
}
