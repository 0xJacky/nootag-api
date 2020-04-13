<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: Upload.php
 * Description:
 * Date: 2020/01/04
 * Time: 11:33 下午
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Upload
 * @package App\Models
 */
class Upload extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'oss_path', 'style', 'size', 'mime_type', 'url', 'user_id'
    ];

    protected $hidden = ['pivot'];


    public function user() {
        return $this->belongsTo(User::class);
    }

}
