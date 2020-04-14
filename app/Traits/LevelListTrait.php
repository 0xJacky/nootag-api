<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: LevelListTrait.php
 * Description:
 * Date: 2020/01/24
 * Time: 1:51 下午
 */

namespace App\Traits;
use App\Handlers\CreditLevelHandler;

trait LevelListTrait
{
    public function get_list()
    {
        return $this->response->array(CreditLevelHandler::load_levels());
    }
}
