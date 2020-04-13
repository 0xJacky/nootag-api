<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: admin/post.php
 * Description: 话题管理的路由
 * Date: 2020/01/10
 * Time: 3:23 下午
 */

// 获取文章列表、附带筛选功能
$api->get('admin/posts', 'PostController@get_list');

// 获取指定文章信息
$api->get('admin/post/{id}', 'PostController@get');

// 文章新建
$api->post('admin/post', 'PostController@store');

// 修改文章信息（管理员的添加和删除）
$api->post('admin/post/{id}', 'PostController@modify');

// 文章删除
$api->delete('admin/post/{id}', 'PostController@destroy');

// 文章反删除
$api->patch('admin/post/{id}', 'PostController@restore');

// 上传 banner
$api->post('admin/post/{id}/banner', '\App\Http\Controllers\Upload\PostController@banner');

// 上传图片
$api->post('admin/post/{id}/images', '\App\Http\Controllers\Upload\PostController@images');

//
$api->delete('admin/post/{id}/image/{upload_id}', '\App\Http\Controllers\Upload\PostController@delete_image');

