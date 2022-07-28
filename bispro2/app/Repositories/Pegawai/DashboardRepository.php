<?php

namespace App\Repositories\Pegawai;

use App\Helpers\ResponseHelpers;

class DashboardRepository
{
    public function getDataPersonal()
    {
        try {
            return ResponseHelpers::ResponseSuccess(
                'Success',
                200,
                auth()
                    ->guard('api_pegawai')
                    ->user()
            );
        } catch (\Exception $e) {
            return ResponseHelpers::ResponseError($e->getMessage(), 400);
        }
    }
}
