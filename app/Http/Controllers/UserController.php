<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: UserController.php
 * Description:
 * Date: 2020/02/22
 * Time: 12:44 下午
 */

namespace App\Http\Controllers;

use App\Transformers\UserTransformer;
use Dingo\Api\Routing\Helpers;

class UserController extends Controller
{
    use Helpers;

    /**
     * 用户信息
     *
     * 获取当前用户的个人信息
     *
     */
    public function info()
    {
        return $this->response
            ->item($this->user(), new UserTransformer());
    }
}
