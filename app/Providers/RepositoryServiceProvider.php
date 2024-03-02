<?php

namespace App\Providers;

use App\Repositories\Contracts\MateriaRepositoryInterface;
use App\Repositories\MateriaRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(MateriaRepositoryInterface::class, MateriaRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
