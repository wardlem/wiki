<?php

namespace Diff;

use Illuminate\Support\ServiceProvider;

class DiffServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('diff', function(){
            return $this->app->make('Diff\Differ');
        });
    }

}