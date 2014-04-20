<?php

namespace Markup;

use Illuminate\Support\ServiceProvider;

class MarkupServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('markup', function(){
            return new WikiMarkup();
        });
    }

}