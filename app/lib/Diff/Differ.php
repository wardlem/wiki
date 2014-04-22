<?php

namespace Diff;

use Diff\PaulButler\SimpleDiff;
use Illuminate\Database\Eloquent\Collection;

class Differ
{

    protected $differ;

    public function __construct(SimpleDiff $differ)
    {
        $this->differ = $differ;
    }

    /**
     * @param $revisions Collection
     *
     * @return string[]
     */
    public function revisions($revisions)
    {
        $test = array();

        foreach ($revisions as $revision){
            $test[] = $revision->content;
        }
        //return $test;
        $html = array();
        for ($i = 0, $l = $revisions->count() - 1; $i < $l; $i++ )
        {
            $html[] = $this->differ->htmlDiff($revisions[$i]->content, $revisions[$i + 1]->content);
        }
        return $html;
    }

    /**
     * Returns the difference between two strings as an array
     *
     * @param $old string
     * @param $new string
     * @return array
     */
    public function diff($old, $new)
    {
        return $this->diffArray(preg_split("/[\s]+/", $old), preg_split("/[\s]+/", $new));
    }

    /**
     * returns the difference of two arrays of strings
     *
     * @param string[] $old
     * @param string[] $new
     * @return array
     */
    public function diffArray(array $old, array $new)
    {
        return $this->differ->diff($old, $new);
    }
}