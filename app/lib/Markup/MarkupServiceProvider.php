<?php

namespace Markup;

use Page;
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
            $markup = new WikiMarkup();

            $markup->registerInternalLinkExists(function($link){
                return Page::find(array('slug' => $link ))->count() > 0;
            });

            $markup->registerCreateInternalLink(function($link){
                return route('articles', $link);
            });

            return $markup;
        });
    }

}