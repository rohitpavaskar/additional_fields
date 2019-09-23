<?php

namespace Rohitpavaskar\AdditionalField;

use Illuminate\Support\ServiceProvider;

class AdditionalFieldServiceProvider extends ServiceProvider {

    /**
     * Publishes configuration file.
     *
     * @return  void
     */
    public function boot() {
        $this->publishes([
            __DIR__ . '/config/additional_fields.php' => config_path('additional_fields.php'),
                ], 'additional_fields');

        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations')
        ]);
    }

    /**
     * Make config publishment optional by merging the config from the package.
     *
     * @return  void
     */
    public function register() {
        $this->mergeConfigFrom(
                __DIR__ . '/config/additional_fields.php', 'additional_fields'
        );
    }
}
