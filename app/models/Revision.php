<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Revision extends WikiModel {

	protected $table = 'revisions';
	public $timestamps = true;
	protected $softDelete = true;

    public function page()
    {
        return $this->belongsTo('Page');
    }

}