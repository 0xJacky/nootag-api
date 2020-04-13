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
       'name', 'multi', 'description'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
