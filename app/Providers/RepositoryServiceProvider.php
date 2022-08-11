<?php

namespace App\Providers;

use App\Repositories\Eloquent;
use App\Repositories\PostRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register repository mapping.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            PostRepositoryInterface::class,
            Eloquent\PostRepository::class
        );
    }

    public function boot()
    {
        //
    }
}
