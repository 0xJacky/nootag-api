<?php

namespace App\Http\Controllers\Admin;

define('CATEGORIES_PER_PAGE', 10);

use App\Http\Controllers\Controller;
use App\Models\Category;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    use Helpers;

    public function __construct()
    {
        $this->middleware('has_power:6');
    }

    /**
     * 添加文章分组
     *
     * 添加文章分类接口
     *
     * @Put("/admin/category")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request({"name":"foo", "description": "bar",
     *      "power": 1}, headers={"Authorization": "Bearer foo"}),
     *      @Response(200, body={"id":1,"created_at":"2020-01-10 23:06:48"}),
     * })
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $request = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'multi' => 'required|sometimes'
        ]);

        $category = Category::create($request);

        return $this->response->array([
            'id' => $category['id'],
            'created_at' => $category->created_at->toDateTimeString()
        ]);
    }

    /**
     * 文章分类信息
     *
     * 获取指定文章分类的信息
     *
     * @Get("/admin/category/{id}")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(headers={"Authorization": "Bearer foo"}),
     *      @Response(200, body={"id":1,"name":"\u6d4b\u8bd5\u6587\u7ae0\u5206\u7c7b","description":"","multi":"\u6d4b\u8bd5 multi","icon":"","created_at":"2020-01-10 23:06:48","updated_at":"2020-01-10 23:06:48","deleted_at":""}),
     * })
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function get($id)
    {
        return $this->response->array(Category::findOrFail($id)->toArray());
    }

    /**
     * 文章分类列表
     *
     * 获取文章分组列表
     *
     * @Get("/admin/categories?page={number}")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request({"name": "可选", "sort": "可选asc或者desc"}, headers={"Authorization": "Bearer foo"}),
     *      @Response(200, body={"current_page":1,"data":{{"id":1,"name":"\u6d4b\u8bd5\u6587\u7ae0\u5206\u7c7b","created_at":"2020-01-10 23:06:48","updated_at":"2020-01-10 23:06:48"},{"id":2,"name":"\u6d4b\u8bd5\u6587\u7ae0\u5206\u7c7b2","created_at":"2020-01-10 23:08:49","updated_at":"2020-01-10 23:08:49"}},"first_page_url":"https:\/\/ibeta-api.app\/admin\/categories?page=1","from":1,"last_page":1,"last_page_url":"https:\/\/ibeta-api.app\/admin\/categories?page=1","next_page_url":null,"path":"https:\/\/ibeta-api.app\/admin\/categories","per_page":10,"prev_page_url":null,"to":2,"total":2}),
     * })
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function get_list(Request $request)
    {
        $request = $request->validate([
            'name' => 'required|sometimes|max:255',
            'sort' => ['required', 'sometimes', Rule::in(['asc', 'desc'])],
            'trashed' => 'sometimes|in:true,false'
        ]);

        $trashed = $request['trashed'] ?? false;
        $sort = $request['sort'] ?? 'asc';
        unset($request['trashed'], $request['sort']);

        $categories = Category::orderBy('id', $sort)->select('id', 'name', 'description', 'created_at', 'updated_at');

        if ($trashed === 'true') {
            $categories->onlyTrashed();
        }

        foreach ($request as $k => $v) {
            $categories->where($k, 'like', '%' . $v . '%');
        }

        $categories = $categories->paginate(CATEGORIES_PER_PAGE);

        return $this->response->array($categories);
    }

    /**
     * 修改文章分类信息
     *
     * 修改指定文章分类的信息
     *
     * @Post("/admin/category/{id}")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request({"name":"可选", "power": "可选",
     *      "description": "可选"}, headers={"Authorization": "Bearer foo"}),
     *      @Response(200, body={"id":2,"name":"\u6d4b\u8bd5\u6587\u7ae0\u5206\u7c7b2","description":"\u6d4b\u8bd5\u63cf\u8ff02","multi":"\u6d4b\u8bd5 multi","icon":"","created_at":"2020-01-10 23:08:49","updated_at":"2020-01-10 23:11:52"}),
     *      @Response(404, body={"message":"No query results for model [App\\Models\\Category] 3","status_code":404}),
     * })
     * @param $id
     * @param Request $request
     * @return mixed
     */

    public function modify($id, Request $request)
    {
        $request = $request->validate([
            'name' => 'required|sometimes|max:255',
            'description' => 'required|sometimes|max:255',
            'multi' => 'required|sometimes|sometimes'
        ]);

        $category = Category::findOrFail($id);

        $category->update($request);

        return $this->response->array($category->toArray());

    }

    /**
     * 删除文章分组
     *
     * 软删除文章分类接口
     *
     * @Delete("/admin/category/{id}")
     * @Versions({"v1"})
     * @Response(204)
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        Category::findOrFail($id);
        return $this->response->noContent();
    }

    /**
     * 反删除文章分组
     *
     * 反删除文章分类接口
     *
     * @Patch("/admin/category/{id}")
     * @Versions({"v1"})
     * @Response(204)
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        Category::onlyTrashed()->findOrFail($id)->restore();
        return $this->response->noContent();
    }
}
