<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: media.php
 * Description:
 * Date: 2020/02/24
 * Time: 12:34 上午
 */

$api->get('admin/medias', 'MediaController@get_list');

$api->get('admin/media/{id}', 'MediaController@get');

$api->delete('admin/media/{id}', 'MediaController@delete');
