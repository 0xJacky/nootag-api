<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Traits\PostList;
use App\Transformers\PostTransformer;
use Dingo\Api\Routing\Helpers;
use Dingo\Blueprint\Annotation\Response;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use Helpers;
    use PostList;

    /**
     * 获取文章列表
     *
     * 获取文章列表接口
     *
     * @Get("/posts")
     * @Versions({"v1"})
     * @Transaction({
     *      @Response(200, body={"current_page":1,"data":{{"id":2,"user_id":1,"title":"\u6587\u7ae0\u6807\u98982","content":"\u5185\u5bb9","post_name":"test_post","banner":null,"post_status":2,"visits":0,"likes":0,"dislikes":0,"comments":0,"allow_comment":1,"push":0,"created_at":"2020-01-19 14:20:35","updated_at":"2020-01-19 14:20:35"},{"id":1,"user_id":1,"title":"\u6587\u7ae0\u6807\u9898","content":"\u5185\u5bb9","post_name":"test_post","banner":null,"post_status":2,"visits":0,"likes":0,"dislikes":0,"comments":0,"allow_comment":1,"push":0,"created_at":"2020-01-10 23:48:17","updated_at":"2020-01-10 23:52:37"}},"first_page_url":"https:\/\/ibeta-api.app\/posts?page=1","from":1,"last_page":1,"last_page_url":"https:\/\/ibeta-api.app\/posts?page=1","next_page_url":null,"path":"https:\/\/ibeta-api.app\/posts","per_page":10,"prev_page_url":null,"to":2,"total":2}),
     * })
     * @param Request $request
     * @return mixed
     */
    public function get_list(Request $request)
    {
        $request = $request->validate([
            'topic_id' => 'sometimes',
            'post_type' => 'sometimes|in:post,contribution',
            'from' => 'sometimes|integer'
        ]);

        return $this->response->array(
            $this->get_posts(
                $request['post_type'] ?? null,
                $request['topic_id'] ?? null,
                $request['from'] ?? null
            )
        );
    }

    /**
     * 获取文章
     *
     * 获取文章接口
     *
     * @Get("/post/{id}")
     * @Versions({"v1"})
     * @Transaction({
     *      @Response(200, body={"id":2,"title":"\u6587\u7ae0\u6807\u98982","content":"\u5185\u5bb9","author":"0xJacky","category":"\u6d4b\u8bd5\u6587\u7ae0\u5206\u7c7b","topic":"\u6d4b\u8bd5\u8bdd\u9898","post_name":"test_post","banner":null,"visits":0,"likes":0,"dislikes":0,"comments_amount":0,"comments":{"current_page":1,"data":{},"first_page_url":"https:\/\/ibeta-api.app\/post\/2?page=1","from":null,"last_page":1,"last_page_url":"https:\/\/ibeta-api.app\/post\/2?page=1","next_page_url":null,"path":"https:\/\/ibeta-api.app\/post\/2","per_page":10,"prev_page_url":null,"to":null,"total":0},"allow_comment":1,"created_at":"2020-01-19 14:20:35","updated_at":"2020-01-19 14:20:35"}),
     *      @Response(404, body={"message":"No query results for model [App\\Models\\Post] 3","status_code":404})
     * })
     * @param mixed $search
     * @return mixed
     */

    public function get($search)
    {
        return $this->response->item(Post::where('id', $search)
            ->orWhere('post_name', $search)->firstOrFail(), new PostTransformer());
    }

}
