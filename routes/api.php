<?php

$dir = __DIR__ . '/api/';

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) use ($dir) {
    $api->get('/', '\App\Http\Controllers\SurfaceController@ping');

    $api->group(['namespace' => 'App\Http\Controllers'], function ($api) use ($dir) {
        foreach (glob($dir . '*.php') as $route) {
            include $route;
        }
    });

    $api->group(['namespace' => 'App\Http\Controllers\Admin',
        'middleware' => 'api.auth'], function ($api) use ($dir) {
        foreach (glob($dir . 'admin/*.php') as $route) {
            include $route;
        }
    });
});
