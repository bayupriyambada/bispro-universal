<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\DepartemenRepository;

class DepartemenController extends Controller
{
    public function getListData()
    {
        $data = (new DepartemenRepository())->getData();
        return $data;
    }

    public function getPostData(Request $request)
    {
        $data = [
            'departemen_id' => $request->input('departemen_id'),
            'name' => $request->input('name'),
            'unit_kerja_id' => $request->input('unit_kerja_id'),
        ];
        $data = (new DepartemenRepository())->getPost($request);
        return $data;
    }

    public function getDeleteData($departemenId)
    {
        $data = [
            'departemen_id' => $departemenId,
        ];
        $data = (new DepartemenRepository())->getDelete($data);
        return $data;
    }

    public function getSearchData(Request $request)
    {
        $data = [
            'cari' => $request->input('cari'),
        ];
        $data = (new DepartemenRepository())->getCariData($data);
        return $data;
    }
}
