<?php

class PageController extends \Illuminate\Routing\Controller
{
    public function homePage()
    {
        return View::make('main', array(
            'categories' => $this->getCategories(),
        ));
    }

    public function page($page, $tab = 'content')
    {
        if (! in_array($tab, array('content', 'revisions', 'discussion'))){
            $tab = 'content';
        }
        if (!$page){
            return false;
        }
        return View::make('page', array(
            'categories' => $this->getCategories(),
            'pageContent' => $page->content,
            'page' => $page,
            'tab' => $tab,
        ));
    }

    protected function getCategories()
    {
        $categories = Category::with('Pages')->get()->sortBy('name');
        return $categories;
    }
}