<?php

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
