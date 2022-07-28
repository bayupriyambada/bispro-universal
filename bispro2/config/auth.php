<?php

return [
    'defaults' => [
        'guard' => 'api_admin',
        'passwords' => 'users',
    ],

    'guards' => [
        'api_admin' => [
            'driver' => 'jwt',
            'provider' => 'users',
            'hash' => false,
        ],
        'api_sdm' => [
            'driver' => 'jwt',
            'provider' => 'sdm',
            'hash' => false,
        ],
        'api_pegawai' => [
            'driver' => 'jwt',
            'provider' => 'pegawai',
            'hash' => false,
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\Models\User::class,
        ],
        'sdm' => [
            'driver' => 'eloquent',
            'model' => \App\Models\SdmModel::class,
        ],
        'pegawai' => [
            'driver' => 'eloquent',
            'model' => \App\Models\PegawaiModel::class,
        ],
    ],
];
