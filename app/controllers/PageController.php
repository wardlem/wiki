<?php

class PageController extends BaseController
{

    public function homePage()
    {
        return View::make('main', array(
            'categories' => $this->getCategories(),
        ));
    }

    public function page($page, $tab = 'content')
    {
        if (! in_array($tab, array('content', 'revisions', 'discussion', 'edit'))){
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

    public function updatePage(Page $page)
    {
        $fields = array('category_id', 'content', 'title', 'slug');
        $values = array();
        foreach ($fields as $field){
            if (Input::get($field) !== $page->{$field}){
                $values[$field] = Input::get($field);
            }
        }
        $v = Validator::make($values, $page->validationRules());
        if ($v->fails()){
            foreach($fields as $field){
                Session::flash($field, Input::get($field));
            }
            return $this->pageRedirect($page, 'edit')
                ->withErrors($v);
        }

        $page->category_id = Input::get('category_id');
        $page->content = Input::get('content');
        $page->title = Input::get('title');
        $page->slug = Input::get('slug');
        $page->save();

        return $this->pageRedirect($page);
    }

    protected function getCategories()
    {
        $categories = Category::with('pages.type')->get()->sortBy('name');
        return $categories;
    }

}