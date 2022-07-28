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
    $router->group(['prefix' => 'pegawai'], function () use ($router) {
        $router->group(['prefix' => 'login'], function () use ($router) {
            $router->post('/', 'Pegawai\AuthController@getLogin');
        });

        $router->group(['middleware' => 'auth:api_pegawai'], function () use (
            $router
        ) {
            $router->group(['prefix' => 'logout'], function () use ($router) {
                $router->post('/', 'Pegawai\AuthController@getLogout');
            });
            $router->group(['prefix' => 'refreshToken'], function () use (
                $router
            ) {
                $router->post('/', 'Pegawai\AuthController@refreshToken');
            });
            $router->group(['prefix' => 'personal'], function () use ($router) {
                $router->get('/', 'Pegawai\AuthController@getPersonalMe');
                $router->post(
                    '/{pegawaiId}/update',
                    'Pegawai\AuthController@getUpdatePersonal'
                );
            });
            $router->group(['prefix' => 'absensi'], function () use ($router) {
                $router->get('/', 'Pegawai\AbsensiController@getListData');
                $router->post(
                    '/in',
                    'Pegawai\AbsensiController@getInAbsensiIn'
                );
                $router->post(
                    '/out',
                    'Pegawai\AbsensiController@getInAbsensiOut'
                );
            });

            // cuti
            $router->group(['prefix' => 'cuti'], function () use ($router) {
                $router->get('/', 'Pegawai\CutiController@getListData');
                $router->post('/simpan', 'Pegawai\CutiController@getPostData');
                $router->delete(
                    '/{cutiId}/hapus',
                    'Pegawai\CutiController@getDeleteData'
                );
                $router->get('/q', 'Pegawai\CutiController@getSearchData');
            });
            // perizinan
            $router->group(['prefix' => 'perizinan'], function () use (
                $router
            ) {
                $router->get('/', 'Pegawai\PerizinanController@getListData');
                $router->post(
                    '/simpan',
                    'Pegawai\PerizinanController@getPostData'
                );
                $router->delete(
                    '/{perizinanId}/hapus',
                    'Pegawai\PerizinanController@getDeleteData'
                );
                $router->get('/q', 'Pegawai\PerizinanController@getSearchData');
            });
            // lembur
            $router->group(['prefix' => 'lembur'], function () use ($router) {
                $router->get('/', 'Pegawai\LemburController@getListData');
                $router->post(
                    '/simpan',
                    'Pegawai\LemburController@getPostData'
                );
                $router->delete(
                    '/{lemburId}/hapus',
                    'Pegawai\LemburController@getDeleteData'
                );
                $router->get('/q', 'Pegawai\LemburController@getSearchData');
            });
        });
    });
});
