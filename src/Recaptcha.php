<?php

namespace Hedii\LaravelRecaptcha;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class Recaptcha
{
    public function __construct(
        private Request $request,
        private string $siteKey,
        private string $secretKey,
        private float $minimumScore
    ) {
    }

    /** @throws \GuzzleHttp\Exception\GuzzleException */
    public function isValid(): bool
    {
        if (! $this->request->has('recaptcha_response')) {
            return false;
        }

        $client = new Client(['connect_timeout' => 5, 'timeout' => 5]);

        try {
            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', ['query' => [
                'secret' => $this->secretKey,
                'response' => $this->request->input('recaptcha_response'),
                'remoteip' => $this->request->ip(),
            ]]);

            $data = json_decode($response->getBody()->getContents(), true);

            if ($data['success'] && $data['score'] >= $this->minimumScore) {
                return true;
            }

            return false;
        } catch (Exception $exception) {
            return false;
        }
    }

    public function script(?string $action = null, string $elementId = 'recaptchaResponse'): string
    {
        return <<<EOD
            <script src="https://www.google.com/recaptcha/api.js?render={$this->siteKey}"></script>
            <script>
                grecaptcha.ready(function () {
                    grecaptcha
                        .execute('{$this->siteKey}', { action: '{$action}' })
                        .then(function (token) {
                            document.getElementById('{$elementId}').value = token
                        })
                })
            </script>
            EOD;

    }
}
