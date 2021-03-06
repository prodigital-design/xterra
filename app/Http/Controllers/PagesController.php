<?php

namespace App\Http\Controllers;

use App\Blog\Article;
use App\Blog\Category;
use App\Blog\Tag;
use App\Slider\Slider;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function home()
    {
        $page = request('page', 1);
        return view('front.blog.index', [
            'categories' => Category::all()->map(function($cat) {
                return [
                    'slug' => $cat->slug,
                    'name' => $cat->title[app()->getLocale()]
                ];
            })->all(),
            'slideshow' => Slider::presentFor(app()->getLocale()),
            'posts'  => app('live-posts')->for(app()->getLocale())->getPage($page),
            'page_title' => trans('blog-index.latest_articles'),
            'page' => $page,
            'seo_title' => trans('seo.home.title'),
            'seo_description' => trans('seo.home.description'),
        ]);
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $cat_name = $category->title[app()->getLocale()];
        return view('front.blog.index', [
            'posts' => app('live-posts')->for(app()->getLocale())->withCategory($category)->getPage(request('page', 1)),
            'categories' => Category::all()->map(function($cat) {
                return [
                    'slug' => $cat->slug,
                    'name' => $cat->title[app()->getLocale()]
                ];
            })->all(),
            'page_title' => $cat_name,
            'seo_title' => trans('seo.category.title', ['category' => $cat_name]),
            'seo_description' => $category->description[app()->getLocale()],
        ]);
    }

    public function tag(Tag $tag)
    {

        return view('front.blog.index', [
            'posts' => app('live-posts')->for(app()->getLocale())->taggedAs($tag)->getPage(request('page', 1)),
            'categories' => Category::all()->map(function($cat) {
                return [
                    'slug' => $cat->slug,
                    'name' => $cat->title[app()->getLocale()]
                ];
            })->all(),
            'tag_title' => $tag->tag_name,
            'all_tags' => Tag::forLang(app()->getLocale())->inUse()->get()->map->toArrayWithCount(),
            'seo_title' => trans('seo.tags.title', ['tag' => $tag->tag_name]),
            'seo_description' => trans('seo.tags.description', ['tag' => $tag->tag_name]),
        ]);
    }

    public function article($article_slug)
    {
        $article = Article::where('slug', $article_slug)->firstOrFail();
        $available_translations = $article->liveTranslations();

        if($available_translations->count() === 0) {
            abort(404);
        }

        $lang = $available_translations->contains(app()->getLocale()) ? app()->getLocale() : $available_translations->first();


        return view('front.blog.show', [
            'article' => app('live-posts')->for($lang)->getPost($article),
            'categories' => Category::all()->map(function($cat) {
                return [
                    'slug' => $cat->slug,
                    'name' => $cat->title[app()->getLocale()]
                ];
            })->all(),
            'in_requested_lang' => $lang === app()->getLocale(),
        ]);
    }
}
