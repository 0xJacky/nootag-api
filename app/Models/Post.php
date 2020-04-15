<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: Post.php
 * Description:
 * Date: 2020/01/05
 * Time: 10:18 下午
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Post extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'title', 'content', 'category_id',
        'post_name', 'post_status',
        'visits'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'category_id', 'post_type', 'deleted_at'
    ];

    private $category;
    private $topic;
    private $title;
    private $content;
    private $post_name;
    private $visits;
    private $likes;
    private $comments;
    private $dislikes;
    private $allow_comment;
    private $user;
    private $comment;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function bannerImg()
    {
        return $this->hasOne(Upload::class,
            'id', 'banner');
    }

    public function images()
    {
        return $this->belongsToMany(Upload::class, 'post_upload', 'post_id', 'upload_id')
            ->using(PostUpload::class)
            ->select('uploads.id', 'name', 'url');
    }

    public function imageCount()
    {
        return $this->images()->count();
    }

    public function visits()
    {
        $this->visit++;
        $this->save();
        return $this->visit;
    }

}
