<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: post.php
 * Description:
 * Date: 2020/01/19
 * Time: 12:8 下午
 */

$api->get('posts', 'PostController@get_list');

$api->get('post/{id}', 'PostController@get');
