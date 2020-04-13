<?php
/**
 * Created by PhpStorm.
 * user.php
 * Description:
 * User: Jacky
 * Date: 2020/3/24
 * Time: 7:28 下午
 */

// 登录并获取 token
$api->post('authorizations', 'AuthorizationsController@store');

// 刷新 token
$api->put('authorizations/current', 'AuthorizationsController@update');

// 注销并删除 token
$api->delete('authorizations/current', 'AuthorizationsController@destroy');
