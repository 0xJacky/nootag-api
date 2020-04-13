<?php

namespace App\Http\Controllers\Upload;

use App\Handlers\UploadHandler;
use App\Http\Controllers\Controller;
use App\Models\PostUpload;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use Helpers;

    public function banner($id, Request $request, UploadHandler $uploader)
    {
        $this->middleware('has_power:6');

        $request->validate([
            'file' => 'required|file|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        $post = Post::findOrFail($id);

        $old = $post->banner;

        $file = $request->file('file');
        $upload = $uploader->store($file, 'banner', $this->user()->id);

        if ($upload['success']) {
            $post->banner = $upload['id'];
            $post->save();
            $uploader->delete($old);
        }

        return $this->response->array($upload);

    }

    public function images($id, Request $request, UploadHandler $uploader)
    {
        $validator = Validator::make($request->all(),
            [
                'files.*' => 'required|mimes:jpg,jpeg,png|max:5000'
            ]);
        $post = Post::find($id);
        if ($this->user()->group->power < 6) {
            $post->where('user_id', $this->user()->id);
        }
        $post = $post->firstOrFail();

        $files = $request->file('files');

        $data['images'] = [];

        foreach ($files as $file) {
            $upload = $uploader->store($file, 'article', $this->user()->id);
            PostUpload::create([
                'post_id' => $post->id,
                'upload_id' => $upload['id']
            ]);
            $data['images'][] = $upload;
        }

        return $this->response->array($data);

    }

    public function delete_image(int $id, int $upload_id, UploadHandler $uploader)
    {
        $uploads = PostUpload::where('post_id', $id)
            ->where('upload_id', $upload_id)->firstOrFail();

        if($this->user()->group->power < 6 && $uploads->post->user->id != $this->user()->id) {
            return $this->response->errorForbidden();
        }

        $uploader->delete($upload_id);

        $post = Post::with('images')->find($id);

        return $this->response->array($post->images->toArray());
    }
}
