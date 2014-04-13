<?php

class Comment extends WikiModel {

	protected $table = 'comments';
	public $timestamps = true;
	protected $softDelete = false;

    public function page()
    {
        return $this->belongsTo('Page');
    }

    public function parentComment()
    {
        return $this->belongsTo('Comment', 'parent_comment_id');
    }

    public function childComments()
    {
        return $this->hasMany('Comment', 'parent_comment_id');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }
}