<?php

/*
 * This file is part of devhub.io.
 *
 * (c) sysatom <sysatom@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Providers;

use App\Support\SphinxEngine;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\EngineManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Sphinx
        resolve(EngineManager::class)->extend('sphinx', function () {
            return new SphinxEngine;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');
        }

        $this->app->bind('App\Repositories\ReposRepository',  'App\Repositories\ReposRepositoryEloquent');
        $this->app->bind('App\Repositories\CategoryRepository', 'App\Repositories\CategoryRepositoryEloquent');
        $this->app->bind('App\Repositories\JobRepository', 'App\Repositories\JobRepositoryEloquent');
    }
}
