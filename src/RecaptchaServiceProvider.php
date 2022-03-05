<?php

namespace Hedii\LaravelRecaptcha;

use Illuminate\Support\ServiceProvider;

class RecaptchaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/recaptcha.php', 'recaptcha');

        $this->app->bind(Recaptcha::class, function ($app) {
            return new Recaptcha(
                $app['request'],
                $app['config']['recaptcha.site_key'],
                $app['config']['recaptcha.secret_key'],
                $app['config']['recaptcha.minimum_score']
            );
        });

        $this->app->alias(Recaptcha::class, 'recaptcha');
    }

    public function boot(): void
    {
        $this->publishes([__DIR__ . '/../config/recaptcha.php' => config_path('recaptcha.php')]);
    }
}
