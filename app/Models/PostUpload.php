<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: PostUpload.php
 * Description:
 * Date: 2020/01/05
 * Time: 10:18 下午
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PostUpload extends Pivot
{
    public $incrementing = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id', 'upload_id'
    ];
}
