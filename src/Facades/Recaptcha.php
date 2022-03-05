<?php

namespace Hedii\LaravelRecaptcha\Facades;

use Illuminate\Support\Facades\Facade;

class Recaptcha extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'recaptcha';
    }
}
