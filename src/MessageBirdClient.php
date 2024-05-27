<?php

namespace Pinetcodev\MessageBird;

use Twilio\Http\CurlClient;

class MessageBirdClient extends CurlClient
{
    public function options(
        string  $method,
        string  $url,
        array   $params = [],
        array   $data = [],
        array   $headers = [],
        ?string $user = null,
        ?string $password = null,
        ?int    $timeout = null,
    ): array
    {
        $adapterUrl = str_replace('https://api.twilio.com/', 'https://eu-west-1.twilio.to.api.bird.com/', $url);

        return parent::options($method, $adapterUrl, $params, $data, $headers, $user, $password, $timeout);
    }
}
