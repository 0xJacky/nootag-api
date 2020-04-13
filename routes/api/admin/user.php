<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: user.php
 * Description:
 * Date: 2020/01/08
 * Time: 6:34 下午
 */

// 获取用户列表、附带筛选功能
$api->get('admin/users', 'UserController@get_list');

// 获取指定用户信息
$api->get('admin/user/{id}', 'UserController@get');

// 用户新建
$api->post('admin/user', 'UserController@store');

// 修改用户信息
$api->post('admin/user/{id}', 'UserController@modify');

// 用户删除
$api->delete('admin/user/{id}', 'UserController@destroy');

