<?php

class CategoryController extends BaseController
{

    public function categoryForm()
    {
        return View::make('create-category', array(
            'categories' => $this->getCategories(),
        ));
    }

    public function createCategory()
    {
        $v = Validator::make(Input::all(), array(
            'name' => 'unique:categories,name|required|max:50'
        ));

        if ($v->fails()){
            Session::flash('name', Input::get('name'));
            return Redirect::route('category.route')->withErrors($v);
        }

        $category = new Category();
        $category->name = Input::get('name');

        $category->save();

        return Redirect::route('home');
    }
}