<?php

class PageType extends WikiModel
{
    protected $table = 'page_types';
    protected $trackUsers = true;

    public function pages()
    {
        return $this->hasMany('Page', 'page_type_id');
    }
}