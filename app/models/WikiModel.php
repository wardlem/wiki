<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Schema\Builder;

class WikiModel extends Eloquent
{

    const MODIFIED_BY = 'mod_by_id';
    const CREATED_BY = 'created_by_id';
    protected $trackUsers = false;
    protected $validation = array();

    protected function performUpdate(\Illuminate\Database\Eloquent\Builder $query)
    {
        if ($this->trackUsers){
            $this->updateUsers();
        }
        return parent::performUpdate($query);
    }

    protected function performInsert(\Illuminate\Database\Eloquent\Builder $query)
    {
        if ($this->trackUsers){
            $this->updateUsers();
        }
        return parent::performInsert($query);
    }


    protected static function boot()
    {
        parent::boot();
        WikiModel::saving(function(WikiModel $model){
            if ($model->trackUsers){
                $model->updateUsers();
            }
        });
    }


    /**
     * Sets the user who last modified the model
     *
     * @param User $user
     */
    public function setModifiedBy(User $user)
    {
        $this->{static::MODIFIED_BY} = $user->id;
    }

    /**
     * Sets the user who created the model
     *
     * @param User $user
     */
    public function setCreatedBy(User $user)
    {
        $this->{static::CREATED_BY} = $user->id;
    }

    /**
     * Returns the user who created the model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo('User', 'id', 'created_by_id');
    }

    /**
     * Returns the user who last modified the model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modifiedBy()
    {
        return $this->belongsTo('User', 'id', 'mod_by_id');
    }

    /**
     * Update the creation and update users.
     *
     * @return void
     */
    public function updateUsers()
    {
        $user = Auth::user();

        if ( ! $this->isDirty(static::MODIFIED_BY))
        {
            $this->setModifiedBy($user);
        }

        if ( ! $this->exists && ! $this->isDirty(static::CREATED_BY))
        {
            $this->setCreatedBy($user);
        }
    }

    public function validationRules()
    {
        return $this->validation;
    }
}