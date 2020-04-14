<?php

namespace App\Http\Controllers\Admin;

define('POSTS_PER_PAGE', 10);

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Transformers\PostListTransformer;
use App\Transformers\PostTransformer;
use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    use Helpers;
    /**
     * 添加文章
     *
     * 添加文章接口
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $request = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|integer|min:1',
            'post_name' => 'required|sometimes|max:255',
            'post_status' => 'required|integer|min:0|max:2'
        ]);

        $append = [
            'user_id' => $this->user()->id
        ];

        $request = array_merge($request, $append);

        $post = Post::create($request);

        return $this->response->item($post, new PostTransformer());
    }

    /**
     * 文章信息
     *
     * 获取指定文章的信息
     *
     * @param $id
     * @return Response
     */
    public function get($id)
    {
        return $this->response->item(Post::findOrFail($id), new PostTransformer());
    }

    /**
     * 文章分类列表
     *
     * 获取文章列表
     *
     * @param Request $request
     * @return Response
     */
    public function get_list(Request $request)
    {
        $request = $request->validate([
            'user_id' => 'sometimes|integer',
            'title' => 'sometimes|max:255',
            'category' => 'sometimes',
            'category_id' => 'sometimes|integer|min:1',
            'post_status' => 'sometimes|integer|between:0,2',
            'post_name' => 'required|sometimes|max:255',
            'order_by' => ['sometimes', Rule::in(['id', 'user_id',
                'category_id',
                'post_status', 'created_at', 'visits'])],
            'sort' => ['sometimes', Rule::in(['asc', 'desc'])],
            'trashed' => 'sometimes|in:true,false'
        ]);

        $trashed = $request['trashed'] ?? false;
        $sort = $request['sort'] ?? 'asc';
        $order_by = $request['order_by'] ?? 'posts.id';
        $category = $request['category'] ?? null;

        unset($request['trashed'], $request['sort'], $request['order_by'], $request['category']);

        $posts = Post::with('category')->orderBy($order_by, $sort);

        if ($trashed === 'true') {
            $posts->onlyTrashed();
        }

        if ($category) {
            $posts->join('categories', 'posts.category_id', '=', 'categories.id')
                ->where('categories.name', 'like', '%' . $category . '%');
        }

        foreach ($request as $k => $v) {
            $posts->where($k, 'like', '%' . $v . '%');
        }

        $posts = $posts->paginate(POSTS_PER_PAGE);

        return $this->response->paginator($posts, new PostListTransformer(), ['key' => 'posts']);
    }

    /**
     * 修改文章信息
     *
     * 修改指定文章的信息
     *
     * @param $id
     * @param Request $request
     * @return mixed
     */

    public function modify($id, Request $request)
    {
        $request = $request->validate([
            'title' => 'sometimes|max:255',
            'content' => 'sometimes',
            'category_id' => 'sometimes|integer|min:1',
            'post_name' => 'sometimes|max:255',
            'post_status' => 'sometimes|integer|min:0|max:2'
        ]);

        $post = Post::findOrFail($id);

        $post->update($request);

        return $this->response->item($post, new PostTransformer());

    }

    /**
     * 删除文章
     *
     * 软删除文章接口
     *
     * @Delete("/admin/post/{id}")
     * @Versions({"v1"})
     * @Response(204)
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function destroy($id, Request $request)
    {
        $request = $request->validate(['mark' => 'sometimes|max:255']);

        $post = Post::findOrFail($id);
        $post->save();

        $post->delete();
        return $this->response->noContent();
    }

    /**
     * 反删除文章
     *
     * 反删除文章接口
     *
     * @Patch("/admin/post/{id}")
     * @Versions({"v1"})
     * @Response(204)
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->restore();
        return $this->response->noContent();
    }
}
