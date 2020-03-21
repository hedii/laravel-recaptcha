<?php

namespace Hedii\LaravelRecaptcha;

use Illuminate\Support\ServiceProvider;

class RecaptchaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/recaptcha.php', 'recaptcha');

        $this->app->bind(Recaptcha::class, function () {
            return new Recaptcha(
                $this->app['request'],
                $this->app['config']['recaptcha.site_key'],
                $this->app['config']['recaptcha.secret_key'],
                $this->app['config']['recaptcha.minimum_score']
            );
        });

        $this->app->alias(Recaptcha::class, 'recaptcha');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([__DIR__ . '/../config/recaptcha.php' => config_path('recaptcha.php')]);
    }
}
