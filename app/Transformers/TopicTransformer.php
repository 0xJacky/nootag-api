<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: TopicListTransformer.php
 * Description:
 * Date: 2020/01/18
 * Time: 2:34 下午
 */

namespace App\Transformers;

use App\Models\Topic;
use League\Fractal\TransformerAbstract;

class TopicTransformer extends TransformerAbstract
{
    public function transform(Topic $target)
    {
        return [
            'id' => $target->id,
            'name' => $target->name,
            'description' => $target->description,
            'multi' => $target->multi,
            'icon' => $target->iconImg->url ?? '',
            'created_at' => $target->created_at->toDateTimeString(),
            'updated_at' => $target->updated_at->toDateTimeString()
        ];

    }

}
