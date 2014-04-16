<?php

namespace Diff;

use Illuminate\Support\Facades\Facade;

class DiffFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'diff';
    }

}