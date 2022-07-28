<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\JenisCutiRepository;

class JenisCutiController extends Controller
{
    public function getListData()
    {
        $data = (new JenisCutiRepository())->getData();
        return $data;
    }

    public function getPostData(Request $request)
    {
        $data = [
            'jenis_cuti_id' => $request->input('jenis_cuti_id'),
            'name' => $request->input('name'),
        ];
        $data = (new JenisCutiRepository())->getPost($request);
        return $data;
    }

    public function getDeleteData($jenisCutiId)
    {
        $data = [
            'jenis_cuti_id' => $jenisCutiId,
        ];
        $data = (new JenisCutiRepository())->getDelete($data);
        return $data;
    }

    public function getSearchData(Request $request)
    {
        $data = [
            'cari' => $request->input('cari'),
        ];
        $data = (new JenisCutiRepository())->getCariData($data);
        return $data;
    }
}
