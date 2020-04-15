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

use App\Handlers\CommentHandler;
use App\Models\Post;
use Dingo\Api\Auth\Auth;
use Illuminate\Support\Facades\Redis;
use League\Fractal\TransformerAbstract;


class PostTransformer extends TransformerAbstract
{
    public function transform(Post $post)
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'content' => $post->content,
            'author' => $post->user->name,
            'category' => $post->category_id ? $post->category->name : null,
            'category_id' => $post->category_id,
            'post_name' => $post->post_name,
            'banner' => $post->banner ? $post->bannerImg->url : null,
            'visits' => $post->visits(),
            'post_status' => $post->post_status,
            'created_at' => $post->created_at->toDateTimeString(),
            'updated_at' => $post->updated_at->toDateTimeString()
        ];
    }

}
