<?php

namespace Markup;

class MarkupFacade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'markup';
    }

}