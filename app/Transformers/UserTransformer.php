<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: UserTransformer.php
 * Description:
 * Date: 2020/01/07
 * Time: 6:3 下午
 */

namespace App\Transformers;

use App\Handlers\CreditLevelHandler;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at->toDateTimeString(),
            'updated_at' => $user->updated_at->toDateTimeString()
        ];
    }

}
