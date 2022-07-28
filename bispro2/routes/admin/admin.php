<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'admin'], function () use ($router) {
        $router->group(['prefix' => 'login'], function () use ($router) {
            $router->post('/', 'Admin\AuthController@getLogin');
        });

        $router->group(['middleware' => 'auth:api_admin'], function () use (
            $router
        ) {
            $router->group(['prefix' => 'dashboard'], function () use (
                $router
            ) {
                $router->get('/', 'Admin\DashboardController@getPersonalData');
                $router->get(
                    '/count-jenis-cuti',
                    'Admin\DashboardController@getCountTotalJenisCuti'
                );
                $router->get(
                    '/count-cuti',
                    'Admin\DashboardController@getCountTotalCuti'
                );
                $router->get(
                    '/count-unit-kerja',
                    'Admin\DashboardController@getCountTotalUnitKerja'
                );
                $router->get(
                    '/count-departemen',
                    'Admin\DashboardController@getCountTotalDepartemen'
                );
                $router->get(
                    '/count-lembur',
                    'Admin\DashboardController@getCountTotalLembur'
                );
                $router->get(
                    '/count-absensi',
                    'Admin\DashboardController@getCountTotalAbsensi'
                );
                $router->get(
                    '/count-perizinan',
                    'Admin\DashboardController@getCountTotalPerizinan'
                );
                $router->get(
                    '/count-sdm',
                    'Admin\DashboardController@getCountTotalSdm'
                );
                $router->get(
                    '/count-pegawai',
                    'Admin\DashboardController@getCountTotalPegawai'
                );
                $router->get(
                    '/count-gaji',
                    'Admin\DashboardController@getCountTotalGaji'
                );
            });
            $router->group(['prefix' => 'logout'], function () use ($router) {
                $router->post('/', 'Admin\AuthController@getLogout');
            });
            $router->group(['prefix' => 'refresh'], function () use ($router) {
                $router->post('/', 'Admin\AuthController@refresh');
            });
            $router->group(['prefix' => 'personal'], function () use ($router) {
                $router->get('/', 'Admin\AuthController@getPersonalMe');
            });

            // unit kerja
            $router->group(['prefix' => 'unit-kerja'], function () use (
                $router
            ) {
                $router->get('/', 'Admin\UnitKerjaController@getListData');
                $router->post(
                    '/simpan',
                    'Admin\UnitKerjaController@getPostData'
                );
                $router->delete(
                    '/{unitKerjaId}/hapus',
                    'Admin\UnitKerjaController@getDeleteData'
                );
                $router->get('/q', 'Admin\UnitKerjaController@getSearchData');
            });

            // departemen
            $router->group(['prefix' => 'departemen'], function () use (
                $router
            ) {
                $router->get('/', 'Admin\DepartemenController@getListData');
                $router->post(
                    '/simpan',
                    'Admin\DepartemenController@getPostData'
                );
                $router->delete(
                    '/{departemenId}/hapus',
                    'Admin\DepartemenController@getDeleteData'
                );
                $router->get('/q', 'Admin\DepartemenController@getSearchData');
            });

            // jenis Cuti
            $router->group(['prefix' => 'jenis-cuti'], function () use (
                $router
            ) {
                $router->get('/', 'Admin\JenisCutiController@getListData');
                $router->post(
                    '/simpan',
                    'Admin\JenisCutiController@getPostData'
                );
                $router->delete(
                    '/{jenisCutiId}/hapus',
                    'Admin\JenisCutiController@getDeleteData'
                );
                $router->get('/q', 'Admin\JenisCutiController@getSearchData');
            });

            // sdm account
            $router->group(['prefix' => 'sdm'], function () use ($router) {
                $router->get('/', 'Admin\SdmController@getListData');
                $router->post('/simpan', 'Admin\SdmController@getPostData');
                $router->post(
                    '/{sdmId}/verification',
                    'Admin\SdmController@getVerificationData'
                );
                $router->delete(
                    '/{sdmId}/hapus',
                    'Admin\SdmController@getDeleteData'
                );
                $router->get('/q', 'Admin\SdmController@getSearchData');
            });
        });
    });
});
