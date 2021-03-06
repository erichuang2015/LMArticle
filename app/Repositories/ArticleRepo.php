<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\Category;

class ArticleRepo extends Repository
{
    public function model()
    {
        return Article::class;
    }

    public static function getList($category = null, $number = null)
    {
        return self::articleQueryForCategory($category, $number)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function getHotList($category = null, $number = null)
    {
        return self::articleQueryForCategory($category, $number)
            ->orderBy('view_number', 'desc')
            ->get();
    }

    public static function getPositionList($positions = [], $category = null, $number = null)
    {
        $query = self::articleQueryForCategory($category, $number)
            ->orderBy('created_at', 'desc');

        foreach ($positions as $position) {
            $query->where('position', '&', $position);
        }

        return $query->get();
    }

    protected static function articleQueryForCategory($category, $number = null)
    {
        $query = Article::query();

        if (empty($number)) {
            $number = (int)setting('right_article_number');
        }

        if (!$category instanceof Category) {
            return $query->take($number);
        }

        $categoryIds = [
            $category->getKey(),
        ];

        if (!$category->childCategory->isEmpty()) {
            $categoryIds = array_merge($categoryIds, $category->childCategory->pluck('id')->toArray());
        }

        return $query->whereIn('category_id', $categoryIds)->take($number);
    }
}
