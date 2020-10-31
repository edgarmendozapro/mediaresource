<?php

namespace EdgarMendozaTech\MediaResource;

use Illuminate\Support\ServiceProvider;

class MediaResourceServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes(
            [
                __DIR__ . '/../config/media_resources.php' => config_path('media_resources.php'),
            ],
            'media_resources_config'
        );
    }
}
