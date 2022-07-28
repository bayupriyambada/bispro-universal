<?php

namespace App\Http\Controllers\Pegawai;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Pegawai\PerizinanRepository;

class PerizinanController extends Controller
{
    public function getListData()
    {
        $data = (new PerizinanRepository())->getListData();
        return $data;
    }
    public function getPostData(Request $request)
    {
        $data = [
            'perizinan_id' => $request->input('perizinan_id'),
            'description_perizinan' => $request->input('description_perizinan'),
            'time_in_perizinan' => $request->input('time_in_perizinan'),
            'time_out_perizinan' => $request->input('time_out_perizinan'),
        ];
        $data = (new PerizinanRepository())->getPostData($request);
        return $data;
    }

    public function getDeleteData($perizinanId)
    {
        $data = [
            'perizinan_id' => $perizinanId,
        ];
        $data = (new PerizinanRepository())->getDelete($data);
        return $data;
    }

    public function getSearchData(Request $request)
    {
        $data = [
            'cari' => $request->input('cari'),
        ];
        $data = (new PerizinanRepository())->getCariData($data);
        return $data;
    }
}
