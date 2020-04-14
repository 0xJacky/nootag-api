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
    public function transform(Post $post)
    {
        $category = $post->category ? $post->category->name : null;
        return [
            'id' => $post->id,
            'title' => $post->title,
            'author' => $post->user->name,
            'category' => $category,
            'post_status' => $post->post_status,
            'visits' => $post->visits(),
            'likes' => $post->likes,
            'dislikes' => $post->dislikes,
            'comments' => $post->comments,
            'allow_comment' => $post->allow_comment,
            'push' => $post->push,
            'created_at' => $post->created_at->toDateTimeString(),
            'updated_at' => $post->updated_at->toDateTimeString()
        ];
    }
}
