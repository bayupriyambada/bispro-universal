<?php

namespace App\Http\Controllers\Pegawai;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Pegawai\CutiRepository;

class CutiController extends Controller
{
    public function getListData()
    {
        $data = (new CutiRepository())->getListData();
        return $data;
    }
    public function getPostData(Request $request)
    {
        $data = [
            'cuti_id' => $request->input('cuti_id'),
            'date_in_cuti' => $request->input('date_in_cuti'),
            'date_out_cuti' => $request->input('date_out_cuti'),
            'type_cuti' => $request->input('type_cuti'),
            'description_cuti' => $request->input('description_cuti'),
        ];
        $data = (new CutiRepository())->getPostData($request);
        return $data;
    }

    public function getDeleteData($cutiId)
    {
        $data = [
            'cuti_id' => $cutiId,
        ];
        $data = (new CutiRepository())->getDelete($data);
        return $data;
    }

    public function getSearchData(Request $request)
    {
        $data = [
            'cari' => $request->input('cari'),
        ];
        $data = (new CutiRepository())->getCariData($data);
        return $data;
    }
}
