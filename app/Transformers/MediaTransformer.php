<?php
/**
 * Copyright (c) 2020 ibeta.me
 * Upload: Jacky
 * Class: MediaTransformer.php
 * Description:
 * Date: 2020/01/07
 * Time: 6:3 下午
 */

namespace App\Transformers\Admin;

use App\Models\Upload;
use League\Fractal\TransformerAbstract;

class MediaTransformer extends TransformerAbstract
{
    public function transform(Upload $upload)
    {
        return [
            'id' => $upload->id,
            'name' => $upload->name,
            'user_id' => $upload->user_id,
            'user' => $upload->user->name,
            'size' => $upload->size,
            'mime_type' => $upload->mime_type,
            'url' => $upload->url,
            'created_at' => $upload->created_at->toDateTimeString(),
            'updated_at' => $upload->updated_at->toDateTimeString(),
        ];
    }

}
