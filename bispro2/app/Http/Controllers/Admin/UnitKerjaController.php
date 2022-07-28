<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\UnitKerjaRepository;

class UnitKerjaController extends Controller
{
    public function getListData()
    {
        $data = (new UnitKerjaRepository())->getData();
        return $data;
    }

    public function getPostData(Request $request)
    {
        $data = [
            'unit_kerja_id' => $request->input('unit_kerja_id'),
            'name' => $request->input('name'),
        ];
        $data = (new UnitKerjaRepository())->getPost($request);
        return $data;
    }

    public function getDeleteData($unitKerjaId)
    {
        $data = [
            'unit_kerja_id' => $unitKerjaId,
        ];
        $data = (new UnitKerjaRepository())->getDelete($data);
        return $data;
    }

    public function getSearchData(Request $request)
    {
        $data = [
            'cari' => $request->input('cari'),
        ];
        $data = (new UnitKerjaRepository())->getCariData($data);
        return $data;
    }
}
