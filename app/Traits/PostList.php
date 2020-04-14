<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: PostList.php
 * Description:
 * Date: 2020/01/19
 * Time: 12:0 下午
 */

namespace App\Traits;

define('POSTS_PER_PAGE_L', 10);

use App\Models\Post;

trait PostList
{

    private function pre_get_post($type = null, int $topic_id = null)
    {
        $post = Post::with(['user' => function ($query) {
            $query->select('id', 'name');
        }, 'user.avatarImg' => function ($query) {
            $query->select('id', 'url');
        }, 'category' => function ($query) {
            $query->select('id', 'name');
        }, 'topic' => function ($query) {
            $query->select('id', 'name');
        }])->select('id', 'title', 'content',
            'user_id', 'category_id', 'topic_id', 'post_name', 'post_status',
            'visits', 'likes', 'dislikes', 'comments', 'allow_comment', 'created_at', 'updated_at')
            ->where('post_status', 2)
            ->orderBy('id', 'desc');

        if ($type) {
            $post->where('post_type', $type);
        }

        if ($topic_id) {
            $post->where('topic_id', $topic_id);
        }

        return $post;
    }

    public function get_posts($type = null, int $topic_id = null, int $from = null)
    {
        $post = $this->pre_get_post($type, $topic_id);

        // 用于固定分页结果
        if ($from) {
            $post->where('id', '<', $from);
        }

        return $post->orderBy('id', 'desc')
            ->paginate(POSTS_PER_PAGE_L);
    }

    public function get_posts_limit($limit = 10, $type = null, int $topic_id = null)
    {
        $posts = $this->pre_get_post($type, $topic_id);
        $posts =  $posts->limit($limit)->get();
        $data = [];
        foreach ($posts as $post) {
            $category = $post->category ? $post->category->name : null;
            $topic = $post->topic ? $post->topic->name : null;
            $content = $post->content;
            $content = mb_strimwidth($content, 0, 120, '...');
            $content = str_replace('#', '', $content);
            $content = ltrim($content, ' ');

            $data[] = [
                'id' => $post->id,
                'title' => $post->title,
                'content' => $content,
                'author' => $post->user->name,
                'category' => $category,
                'topic' => $topic,
                'post_name' => $post->post_name,
                'post_status' => $post->post_status,
                'visits' => $post->visits,
                'likes' => $post->likes,
                'dislikes' => $post->dislikes,
                'comments' => $post->comments,
                'allow_comment' => $post->allow_comment,
                'created_at' => $post->created_at->toDateTimeString(),
                'updated_at' => $post->updated_at->toDateTimeString()
            ];
        }

        return $data;
    }
}
