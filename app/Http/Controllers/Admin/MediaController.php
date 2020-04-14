<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: MediaController.php
 * Description:
 * Date: 2020/02/23
 * Time: 11:59 下午
 */

/**
 * Created by PhpStorm.
 * MediaController.php
 * Description:
 * User: Jacky
 * Date: 2020/2/23
 * Time: 11:59 下午
 */

namespace App\Http\Controllers\Admin;

use App\Handlers\UploadHandler;
use App\Http\Controllers\Controller;
use App\Transformers\Admin\MediaTransformer;
use Illuminate\Http\Request;
use App\Models\Upload;
use Dingo\Api\Routing\Helpers;

class MediaController extends Controller
{
    use Helpers;

    function __construct()
    {
        $this->middleware('has_power:6');
    }

    public function get_list(Request $request)
    {
        $request = $request->validate([
            'user_id' => 'exists:users,id',
            'mime_type' => 'in:image,application',
            'sort' => 'sometimes|in:asc,desc'
        ]);

        $sort = $request['sort'] ?? 'desc';
        unset($request['sort']);

        $media = Upload::orderBy('id', $sort)
            ->select('id', 'mime_type', 'url', 'created_at', 'updated_at');

        foreach ($request as $k => $v) {
            $media->where($k, 'like', '%' . $v . '%');
        }

        $media = $media->paginate(20);

        return $this->response->array($media);
    }

    public function get($id)
    {
        return $this->response->item(Upload::findOrFail($id), new MediaTransformer());
    }

    public function delete($id, UploadHandler $uploader)
    {
        $uploader->delete($id);

        return $this->response->noContent();
    }

}
