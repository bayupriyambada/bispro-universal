<?php

namespace App\Http\Controllers\Sdm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Sdm\PegawaiRepository;

class PegawaiController extends Controller
{
    public function getListVerifikasiAccount()
    {
        $data = (new PegawaiRepository())->getAccountActive();
        return $data;
    }
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
            'password' => $request->input('password'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'date_birth' => $request->input('date_birth'),
            'pegawai_position_id' => $request->input('pegawai_position_id'),
        ];
        $data = (new PegawaiRepository())->getPost($request);
        return $data;
    }
    public function getVerificationData($pegawaiId)
    {
        $data = [
            'pegawai_id' => $pegawaiId,
        ];
        $data = (new PegawaiRepository())->getVerificationAccount($data);
        return $data;
    }
    public function getDeleteData($pegawaiId)
    {
        $data = [
            'pegawai_id' => $pegawaiId,
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
