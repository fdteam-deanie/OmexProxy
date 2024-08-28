<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'news_categories';

    protected $fillable = [
        'name',
        'is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
