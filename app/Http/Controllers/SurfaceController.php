<?php

namespace App\Http\Controllers;

use App\Traits\PostList;
use App\Transformers\PostListTransformer;
use App\Transformers\PostTransformer;
use Illuminate\Http\Request;
use App\Models\Donate;
use App\Models\Post;
use App\Models\Topic;
use App\Transformers\DonateListTransformer;
use Dingo\Api\Routing\Helpers;


class SurfaceController extends Controller
{
    use Helpers;

    public function ping()
    {
        return $this->response->array(['message' => 'ok']);
    }

    public function landing()
    {
        $posts = Post::where('post_status', 2)
            ->limit(5)->orderBy('id', 'desc')->get();

        $data['posts'] = [];

        foreach ($posts as $post) {
            $data['posts'][] = PostListTransformer::transform($post);
        }

        return $this->response->array($data);
    }
}
