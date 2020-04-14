<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: Category.php
 * Description:
 * Date: 2020/01/05
 * Time: 10:18 下午
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
