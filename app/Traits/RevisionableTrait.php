<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: RevisionableTrait.php
 * Description:
 * Date: 2020/01/22
 * Time: 7:46 下午
 */

/**
 * Created by PhpStorm.
 * RevisionableTrait.php
 * Description:
 * User: Jacky
 * Date: 2020/1/22
 * Time: 7:46 下午
 */

namespace App\Traits;


use Dingo\Api\Auth\Auth;

trait RevisionableTrait
{
    use \Venturecraft\Revisionable\RevisionableTrait {
        \Venturecraft\Revisionable\RevisionableTrait::getSystemUserId as traitGetSystemUserId;
    }

    // overridden getSystemUserId()
    private function getSystemUserId()
    {
        $user_id = $this->traitGetSystemUserId();

        if(is_null($user_id)) {
            $user_id = app(Auth::class)->user()->id ?? 0;
        }

        return $user_id;
    }

}
