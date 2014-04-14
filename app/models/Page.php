<?php

class Page extends WikiModel
{
    protected $table = 'pages';
    public $timestamps = true;
    protected $trackUsers = true;
    protected $softDelete = true;
    protected $validation = array(
        'category_id' => 'sometimes|exists:categories,id|required',
        'slug' => 'sometimes|alpha_dash|unique:pages|required',
        'title' => 'sometimes|required|unique:pages',
        'page_type_id' => 'sometimes|required'
    );

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
        return $this->belongsTo('PageType', 'page_type_id');
    }

    public function render()
    {

       return Markdown::parse($this->content);
    }


}