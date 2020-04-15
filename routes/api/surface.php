<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: surface.php
 * Description:
 * Date: 2020/02/24
 * Time: 8:32 下午
 */

$api->get('/', 'SurfaceController@ping');

$api->get('surface/landing', 'SurfaceController@landing');
