<?php

namespace Hedii\LaravelRecaptcha\Tests;

use Hedii\LaravelRecaptcha\Recaptcha;
use Hedii\LaravelRecaptcha\RecaptchaServiceProvider;
use Orchestra\Testbench\TestCase;

class RecaptchaTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [RecaptchaServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('recaptcha.site_key', env('RECAPTCHA_SITE_KEY'));
        $app['config']->set('recaptcha.site_secret', env('RECAPTCHA_SECRET_KEY'));
        $app['config']->set('recaptcha.minimum_score', env('RECAPTCHA_MINIMUM_SCORE'));
    }

    /** @test */
    public function it_should_have_recaptcha_bind_in_the_container(): void
    {
        $this->assertInstanceOf(Recaptcha::class, $this->app['recaptcha']);
        $this->assertInstanceOf(Recaptcha::class, $this->app[Recaptcha::class]);
    }

    /** @test */
    public function it_should_have_registered_a_facade(): void
    {
        $this->assertSame(
            $this->app->make(Recaptcha::class)->script(),
            \Hedii\LaravelRecaptcha\Facades\Recaptcha::script()
        );
    }
}
