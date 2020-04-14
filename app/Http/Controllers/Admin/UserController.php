<?php

namespace App\Http\Controllers\Admin;

define('USERS_PER_PAGE', 10);

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Transformers\UserListTransformer;
use App\Transformers\UserTransformer;
use Dingo\Api\Routing\Helpers;
use Dingo\Blueprint\Annotation\Method\Delete;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use Helpers;

    /**
     * 添加用户
     * 添加用户接口
     *
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $request = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            'password' => 'required|min:8|max:255',
        ]);

        $request['password'] = bcrypt($request['password']);

        $user = User::create($request);

        return $this->response->array([
            'id' => $user['id'],
            'created_at' => $user->created_at->toDateTimeString()
        ]);
    }

    /**
     * 修改用户信息
     * 修改用户的个人信息
     *
     * @param $id
     * @param Request $request
     * @return mixed
     */

    public function modify($id, Request $request)
    {
        $request = $request->validate([
            'name' => 'required|sometimes|max:255',
            'email' => 'required|sometimes|email',
            'user_group_id' => 'sometimes|integer'
        ]);

        $user = User::findOrFail($id);

        $user->update($request);

        return $this->response->item($user, new UserTransformer());

    }

    /**
     * 用户信息
     * 获取指定用户的个人信息
     *
     * @param $id
     * @return mixed
     */

    public function get($id)
    {
        $user = User::findOrFail($id);

        return $this->response->item($user, new UserTransformer());
    }


    /**
     * 用户列表
     *
     * 获取用户列表
     *
     * @param Request $request
     * @return mixed
     */
    public function get_list(Request $request)
    {
        $request = $request->validate([
            'name' => 'sometimes|max:255|nullable',
            'email' => 'sometimes|nullable',
            'trashed' => 'sometimes|boolean',
            'order_by' => ['sometimes', Rule::in(['id', 'name'])],
            'sort' => ['sometimes', Rule::in(['asc', 'desc'])]
        ]);

        $trashed = $request['trashed'] ?? false;
        $order_by = $request['order_by'] ?? 'id';
        $sort = $request['sort'] ?? 'asc';

        unset($request['trashed'], $request['order_by'], $request['sort']);

        $users = User::orderBy($order_by, $sort);

        if ($trashed) {
            $users = $users->onlyTrashed();
        }

        foreach ($request as $k => $v) {
            $users->where($k, 'like', '%' . $v . '%');
        }

        $users = $users->paginate(USERS_PER_PAGE);

        return $this->response->paginator($users, new UserListTransformer(), ['key' => 'users']);
    }

    /**
     * 删除用户
     *
     * 软删除用户接口
     *
     * @Delete("/admin/user/{id}")
     * @Versions({"v1"})
     * @Response(204)
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return $this->response->noContent();
    }
}
