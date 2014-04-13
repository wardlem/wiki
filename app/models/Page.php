<?php

class Page extends WikiModel
{
    protected $table = 'pages';
    public $timestamps = true;
    protected $trackUsers = true;
    protected $softDelete = true;

    public function category()
    {
        return $this->belongsTo('Category');
    }

    public function comments()
    {
        return $this->hasMany('Comment');
    }

    public function topLevelComments()
    {
        return Comment::where('page_id', '=', $this->id)->whereRaw('parent_comment_id IS NULL')->get();
    }

    public function revisions()
    {
        return $this->hasMany('Revision');
    }

    public function type()
    {
        return $this->belongsTo('PageType');
    }

    public function render()
    {
       return $this->content;
    }


}