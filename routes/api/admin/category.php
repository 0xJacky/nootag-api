<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: admin/category.php
 * Description: 话题管理的路由
 * Date: 2020/01/10
 * Time: 3:23 下午
 */

// 获取文章分组列表、附带筛选功能
$api->get('admin/categories', 'CategoryController@get_list');

// 获取指定文章分组信息
$api->get('admin/category/{id}', 'CategoryController@get');

// 文章分组新建
$api->post('admin/category', 'CategoryController@store');

// 修改文章分组信息（管理员的添加和删除）
$api->post('admin/category/{id}', 'CategoryController@modify');

// 文章分组删除
$api->delete('admin/category/{id}', 'CategoryController@destroy');

// 文章分组反删除
$api->patch('admin/category/{id}', 'CategoryController@restore');

