<?php

class Category extends WikiModel {

	protected $table = 'categories';
	public $timestamps = true;
	protected $softDelete = false;
    protected $trackUsers = true;

    public function pages()
    {
        return $this->hasMany('Page');
    }

    public function slugify()
    {
        return str_replace(' ', '-', strtolower($this->name));
    }

    public function dumpPages()
    {
        return print_r($this->pages, true);
    }

    public function getPages()
    {
        return $this->pages;
    }

}