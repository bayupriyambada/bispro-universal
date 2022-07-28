<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Admin\SdmRepository;

class SdmController extends Controller
{
    public function getListData()
    {
        $data = (new SdmRepository())->getData();
        return $data;
    }

    public function getPostData(Request $request)
    {
        $data = [
            'sdm_id' => $request->input('sdm_id'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'date_birth' => $request->input('date_birth'),
        ];
        $data = (new SdmRepository())->getPost($request);
        return $data;
    }

    public function getVerificationData($sdmId)
    {
        $data = [
            'sdm_id' => $sdmId,
        ];
        $data = (new SdmRepository())->getVerificationAccount($data);
        return $data;
    }

    public function getDeleteData($sdmId)
    {
        $data = [
            'sdm_id' => $sdmId,
        ];
        $data = (new SdmRepository())->getDelete($data);
        return $data;
    }

    public function getSearchData(Request $request)
    {
        $data = [
            'cari' => $request->input('cari'),
        ];
        $data = (new SdmRepository())->getCariData($data);
        return $data;
    }
}
