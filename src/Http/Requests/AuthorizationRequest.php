<?php

namespace Oza75\OrangeSMSChannel\Http\Requests;


use Oza75\OrangeSMSChannel\Http\OrangeSMSClientRequest;

class AuthorizationRequest extends OrangeSMSClientRequest
{
    /**
     * @var string
     */
    private string $clientID;

    /**
     * @var string
     */
    private string $clientSecret;

    /**
     * AuthorizeClientRequest constructor.
     * @param $clientID
     * @param $clientSecret
     */
    public function __construct($clientID, $clientSecret)
    {
        $this->clientID = $clientID;
        $this->clientSecret = $clientSecret;
    }

    /**
     * Http request method.
     *
     * @return string
     */
    public function method(): string
    {
        return 'POST';
    }

    /**
     * The uri for the current request.
     *
     * @return string
     */
    public function uri(): string
    {
        return static::BASE_URI . '/oauth/v3/token';
    }

    /**
     * Http request options.
     *
     * @return array
     */
    public function options(): array
    {
        return [
            'headers' => [
                'Authorization' => "Basic " . base64_encode("{$this->clientID}:{$this->clientSecret}"),
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json'
            ],
            'form_params' => [
                'grant_type' => 'client_credentials'
            ]
        ];
    }
}
