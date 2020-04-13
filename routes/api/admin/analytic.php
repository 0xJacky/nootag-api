<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: analytic.php
 * Description:
 * Date: 2020/02/06
 * Time: 2:25 下午
 */

$api->get('admin/analytic', 'AnalyticController@get');

$api->get('admin/analytic/mysql', 'AnalyticController@mysql');

$api->get('admin/analytic/server', 'AnalyticController@server');

$api->get('admin/analytic/google', 'AnalyticController@google');
