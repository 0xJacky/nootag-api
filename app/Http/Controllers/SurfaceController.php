<?php

namespace App\Http\Controllers;

use App\Traits\PostList;
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
    use PostList;

    public function ping()
    {
        return $this->response->array(['message' => 'ok']);
    }

    public function landing()
    {
        $data['post'] = $this->get_posts_limit(20);

        $topics = Topic::with(['cards' => function ($query) {
            $query->where('visible', 1);
            $query->select('id', 'name', 'url', 'img_id', 'topic_id', 'build_id',
                'pub_time', 'type', 'button', 'css', 'download_method', 'download_times');
        }, 'iconImg' => function ($query) {
            $query->select('id', 'url');
        }, 'cards.icon' => function ($query) {
            $query->select('id', 'url');
        }])->orderBy('id', 'asc')->get();

        foreach ($topics as $topic) {

            $t = [
                'id' => $topic->id,
                'name' => $topic->name,
                'description' => $topic->description,
            ];

            foreach ($topic->cards as $card) {
                $t['cards'][] = [
                    'id' => $card->id,
                    'name' => $card->name,
                    'url' => $card->url,
                    'icon' => $card->icon ? $card->icon->url : null,
                    'build_id' => $card->build_id,
                    'pub_time' => $card->pub_time,
                    'type' => $card->type,
                    'button' => $card->button,
                    'css' => $card->css,
                    'download_method' => (int)$card->download_method,
                    'download_times' => $card->download_times,
                ];
            }

            $data['topics'][] = $t;

        }

        return $this->response->array($data);

    }

    public function donate(Request $request)
    {
        $request = $request->validate([
            'group' => 'required|between:0,2'
        ]);

        $min = 0;
        $max = 10;

        switch ($request['group']) {
            case 0:
                $min = 0;
                $max = 10;
                break;
            case 1:
                $min = 10;
                $max = 100;
                break;
            case 2:
                $min = 100;
                $max = false;
                break;
        }

        $donates = Donate::orderBy('id', 'desc')->where('money', '>', $min);
        if ($max) {
            $donates->where('money', '<', $max);
        }
        $donates = $donates->paginate(15);

        return $this->response->paginator($donates, new DonateListTransformer(), ['key' => 'donates']);
    }

    public function about()
    {
        return $this->response->item(
            Post::findOrFail(settings('about_post_id', 1)), new PostTransformer()
        );
    }
}
