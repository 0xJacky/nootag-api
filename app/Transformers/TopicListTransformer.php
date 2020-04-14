<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: TopicListTransformer.php
 * Description:
 * Date: 2020/01/18
 * Time: 2:34 下午
 */

/**
 * Created by PhpStorm.
 * TopicListTransformer.php
 * Description:
 * User: Jacky
 * Date: 2020/1/18
 * Time: 2:34 下午
 */

namespace App\Transformers;


use App\Models\Subscribe_topic;
use App\Models\Topic;
use League\Fractal\TransformerAbstract;

class TopicListTransformer extends TransformerAbstract
{
    public function transform(Topic $target)
    {
        return [
            'id' => $target->id,
            'name' => $target->name,
            'description' => $target->description,
            'icon' => $target->iconImg->url ?? '',
            'subscribed_at' => $target->pivot->created_at->toDateTimeString()
        ];

    }

}
