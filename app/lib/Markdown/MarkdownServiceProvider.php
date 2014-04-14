<?php

namespace Markdown;

use Illuminate\Support\ServiceProvider;

class MarkdownServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('markdown', function(){
            return new WikiMarkdown();
        });
    }

}