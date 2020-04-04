[![Build Status](https://travis-ci.org/hedii/laravel-recaptcha.svg?branch=master)](https://travis-ci.org/hedii/laravel-recaptcha)

# Laravel Recaptcha

Google reCAPTCHA v3 for Laravel 7.0+

## Table of contents

- [Table of contents](#table-of-contents)
- [Installation](#installation)
- [Usage](#usage)
  - [Example](#example)
- [License](#license)

## Installation

Install via [composer](https://getcomposer.org/doc/00-intro.md)

```sh
composer require hedii/laravel-recaptcha
```

Publish the configuration

```sh
php artisan vendor:publish --provider="Hedii\LaravelRecaptcha\RecaptchaServiceProvider"
```

The configuration file located at `config/recaptcha.php` looks like this:

```php
return [
    /**
     * The recaptcha site key.
     */
    'site_key' => env('RECAPTCHA_SITE_KEY', ''),

    /**
     * The recaptcha site secret.
     */
    'secret_key' => env('RECAPTCHA_SECRET_KEY', ''),

    /**
     * The minimum score (from 0.0 to 1.0) a recaptcha response must have to be
     * valid. 1.0 is very likely a good interaction, 0.0 is very likely a bot.
     */
    'minimum_score' => env('RECAPTCHA_MINIMUM_SCORE', 0.7),
];
```

Register a new website on [Google Recaptcha admin](https://www.google.com/recaptcha/admin/create), and select reCAPTCHA v3

Edit `.env` to add the required environment variables

```
RECAPTCHA_SITE_KEY=xxxxxxxxxxxxxxxxxxx
RECAPTCHA_SECRET_KEY=xxxxxxxxxxxxxxxxxxx
RECAPTCHA_MINIMUM_SCORE=0.7
```

## Usage

Insert the required javascript on a html form using the following method. You can set an action name as the first parameter, and the id of the recaptcha hidden html field as a second parameter.

```php
<form method="post">
    <!-- ...the rest of the form... -->

    {!! Recaptcha::script('contact_form', 'recaptchaResponse') !!}

    <!-- make sure you place a hidden input nammed recaptcha_response  -->
    <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

    <input type="submit">
</form>
```

On your controller, you can verify the recaptcha score like this:

```php
public function store(\Hedii\LaravelRecaptcha\Recaptcha $recaptcha)
{
    if (! $recaptcha->isValid()) {
        // the recaptcha score is lower than the configured minimal score, you
        // can throw a validation exception or do anything else 
        throw ValidationException::withMessages([
            'recaptcha' => 'Recaptcha validation failed...'
        ]);
    }

    // here the score is valid
}
```

If you prefer to use the Facade:

```php
public function store()
{
    if (! Recaptcha::isValid()) {
        // the recaptcha score is lower than the configured minimal score, you
        // can throw a validation exception or do anything else 
        throw ValidationException::withMessages([
            'recaptcha' => 'Recaptcha validation failed...'
        ]);
    }

    // here the score is valid
}
```

## License

laravel-recaptcha is released under the MIT Licence. See the bundled [LICENSE](https://github.com/hedii/laravel-recaptcha/blob/master/LICENSE.md) file for details.
