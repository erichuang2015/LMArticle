<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Repositories\SettingRepo;

class CategoryController extends BaseController
{
    public function show($category)
    {
        $urlType = setting('category_url_type');

        if ($urlType == Category::URL_TYPE_NAME) {
            $category = Category::where('name', $category)->first();
        } else {
            $category = Category::find($category);
        }

        if (empty($category)) {
            abort(404);
        }

        $articleNumber = setting('article_list_number');
        $articles = Article::where('category_id', $category->getKey())->paginate($articleNumber);
        $title = str_replace('{$category.display_name$}', $category->display_name, setting('site_title_category'));

        return view('category.show', [
            'category' => $category,
            'articles' => $articles,
            'title' => $title,
        ]);
    }
}
