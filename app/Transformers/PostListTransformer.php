<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: PostListTransformer.php
 * Description:
 * Date: 2020/02/20
 * Time: 3:13 下午
 */

namespace App\Transformers;

use App\Models\Post;
use League\Fractal\TransformerAbstract;

class PostListTransformer extends TransformerAbstract
{
    public static function transform(Post $post)
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'author' => $post->user->name,
            'banner' => $post->banner ? $post->bannerImg->url : null,
            'category' => $post->category ? $post->category->name : null,
            'post_status' => $post->post_status,
            'visits' => $post->visits(),
            'created_at' => $post->created_at->toDateString(),
            'updated_at' => $post->updated_at->toDateString()
        ];
    }
}
