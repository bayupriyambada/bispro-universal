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
    $router->group(['prefix' => 'sdm'], function () use ($router) {
        $router->group(['prefix' => 'login'], function () use ($router) {
            $router->post('/', 'Sdm\AuthController@getLogin');
        });

        $router->group(['middleware' => 'auth:api_sdm'], function () use (
            $router
        ) {
            $router->group(['prefix' => 'logout'], function () use ($router) {
                $router->post('/', 'Sdm\AuthController@getLogout');
            });
            $router->group(['prefix' => 'personal'], function () use ($router) {
                $router->get('/', 'Sdm\AuthController@getPersonalMe');
                $router->post(
                    '/{sdmId}/update',
                    'Sdm\AuthController@getUpdatePersonal'
                );
            });
            // pegawai account
            $router->group(['prefix' => 'pegawai-account'], function () use (
                $router
            ) {
                $router->get('/', 'Sdm\PegawaiController@getListData');
                $router->get(
                    '/account',
                    'Sdm\PegawaiController@getListVerifikasiAccount'
                );
                $router->post('/simpan', 'Sdm\PegawaiController@getPostData');
                $router->post(
                    '/{pegawaiId}/verification',
                    'Sdm\PegawaiController@getVerificationData'
                );
                $router->delete(
                    '/{pegawaiId}/hapus',
                    'Sdm\PegawaiController@getDeleteData'
                );
                $router->get('/q', 'Sdm\PegawaiController@getSearchData');
            });

            // get data to verification
            $router->group(['prefix' => 'get-verification'], function () use (
                $router
            ) {
                $router->group(['prefix' => 'absensi'], function () use (
                    $router
                ) {
                    $router->get(
                        '/',
                        'Sdm\VerificationController@getCanVerificationAbsensi'
                    );
                });
                $router->group(['prefix' => 'lembur'], function () use (
                    $router
                ) {
                    $router->get(
                        '/',
                        'Sdm\VerificationController@getCanVerificationLembur'
                    );
                });
                $router->group(['prefix' => 'cuti'], function () use ($router) {
                    $router->get(
                        '/',
                        'Sdm\VerificationController@getCanVerificationCuti'
                    );
                });
                $router->group(['prefix' => 'perizinan'], function () use (
                    $router
                ) {
                    $router->get(
                        '/',
                        'Sdm\VerificationController@getCanVerificationPerizinan'
                    );
                });
            });
            // get verified
            $router->group(['prefix' => 'verified'], function () use ($router) {
                $router->group(['prefix' => 'absensi'], function () use (
                    $router
                ) {
                    $router->get(
                        '/',
                        'Sdm\VerifiedController@getVerificationAbsensi'
                    );
                });
                $router->group(['prefix' => 'lembur'], function () use (
                    $router
                ) {
                    $router->get(
                        '/',
                        'Sdm\VerifiedController@getVerificationLembur'
                    );
                });
                $router->group(['prefix' => 'cuti'], function () use ($router) {
                    $router->get(
                        '/',
                        'Sdm\VerifiedController@getVerificationCuti'
                    );
                });
                $router->group(['prefix' => 'perizinan'], function () use (
                    $router
                ) {
                    $router->get(
                        '/',
                        'Sdm\VerifiedController@getVerificationPerizinan'
                    );
                });
            });

            // generate penggajian
            $router->group(['prefix' => 'penggajian'], function () use (
                $router
            ) {
                $router->get('/', 'Sdm\PenggajianController@getDataGajiUser');
                // $router->get('/', 'Sdm\PenggajianController@getDataUser');
                $router->get(
                    '/user',
                    'Sdm\PenggajianController@getDataPegawai'
                );
                $router->post(
                    '/generate',
                    'Sdm\PenggajianController@getPenggajianUser'
                );
            });
        });
    });
});
