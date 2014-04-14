<?php

namespace Markdown;

class MarkdownFacade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'markdown';
    }

}