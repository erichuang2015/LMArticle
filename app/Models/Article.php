<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title', 'category_id', 'content', 'position', 'keywords',
    ];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
