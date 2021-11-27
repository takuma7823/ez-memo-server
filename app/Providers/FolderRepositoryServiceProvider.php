<?php

namespace App\Providers;

use App\Protocols\FolderRepositoryProtocol;
use App\Repositories\FolderRepository;
use Illuminate\Support\ServiceProvider;


class FolderRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FolderRepositoryProtocol::class, FolderRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}