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

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserListTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'user_group' => $user->group->name,
            'level' => $user->level,
            'email_verify' => $user->email_verified_at ? true : false,
            'active' => $user->active === 1 ? true : false,
            'credits' => $user->credits,
            'last_active' => $user->last_active(),
            'created_at' => $user->created_at->toDateTimeString()
        ];
    }

}
