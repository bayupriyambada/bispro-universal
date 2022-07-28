<?php

namespace App\Http\Controllers\Pegawai;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Pegawai\LemburRepository;

class LemburController extends Controller
{
    public function getListData()
    {
        $data = (new LemburRepository())->getListData();
        return $data;
    }
    public function getPostData(Request $request)
    {
        $data = [
            'lembur_id' => $request->input('lembur_id'),
            'time_in' => $request->input('time_in'),
            'time_out' => $request->input('time_out'),
            'assignment_description' => $request->input(
                'assignment_description'
            ),
        ];
        $data = (new LemburRepository())->getPostData($request);
        return $data;
    }

    public function getDeleteData($departemenId)
    {
        $data = [
            'departemen_id' => $departemenId,
        ];
        $data = (new LemburRepository())->getDelete($data);
        return $data;
    }

    public function getSearchData(Request $request)
    {
        $data = [
            'cari' => $request->input('cari'),
        ];
        $data = (new LemburRepository())->getCariData($data);
        return $data;
    }
}
