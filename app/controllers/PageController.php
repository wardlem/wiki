<?php

class PageController extends \Illuminate\Routing\Controller
{
    public function homePage()
    {
        return View::make('main', array(
            'categories' => $this->getCategories(),
        ));
    }

    public function page($slug)
    {
        $page = Page::where('slug', 'LIKE', $slug)->first();
        if (!$page){
            return false;
        }
        return View::make('page', array(
            'categories' => $this->getCategories(),
            'pageContent' => $page->content,
            'page' => $page,
        ));
    }

    protected function getCategories()
    {
        $categories = Category::with('Pages')->get()->sortBy('name');
        return $categories;
    }
}