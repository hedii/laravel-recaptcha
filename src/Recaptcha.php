<?php

namespace Hedii\LaravelRecaptcha;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class Recaptcha
{
    /**
     * The http request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private Request $request;

    /**
     * The recaptcha site key.
     *
     * @var string
     */
    private string $siteKey;

    /**
     * The recaptcha secret key.
     *
     * @var string
     */
    private string $secretKey;

    /**
     * The minimum score a recaptcha response must have to be valid.
     *
     * @var float
     */
    private float $minimumScore;

    /**
     * Recaptcha constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $siteKey
     * @param string $secretKey
     * @param float $minimumScore
     */
    public function __construct(Request $request, string $siteKey, string $secretKey, float $minimumScore)
    {
        $this->request = $request;
        $this->siteKey = $siteKey;
        $this->secretKey = $secretKey;
        $this->minimumScore = $minimumScore;
    }

    /**
     * Resolve the captcha score.
     *
     * @return bool
     */
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

    /**
     * Get the required recaptcha js scripts.
     *
     * @param string|null $action
     * @param string $elementId
     * @return string
     */
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
