<?php

use Illuminate\Support\Collection;

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
    );

    public static function boot()
    {
        Page::saved(function($page){
            $page->createRevision();
        });
    }

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

    public function createRevision()
    {
        $last = $this->getLatestRevision();
        if (is_null($last) || $this->content !== $this->getLatestRevision()->content){
            $revision = new Revision();
            $revision->page_id = $this->id;
            $revision->created_by_id = $this->created_by_id;
            $revision->content = $this->content;
            $revision->save();
        }
    }

    /**
     * Gets all the revisions made for the page
     *
     * @return Collection
     */
    public function getRevisions()
    {
        return $this->revisions->sortBy('created_at');
    }

    /**
     * Gets the last revision made for the page
     *
     * @return Revision
     */
    public function getLatestRevision()
    {
        return $this->getRevisions()->last();
    }

}