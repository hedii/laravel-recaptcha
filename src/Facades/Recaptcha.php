<?php

namespace Hedii\LaravelRecaptcha\Facades;

use Illuminate\Support\Facades\Facade;

class Recaptcha extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'recaptcha';
    }
}
