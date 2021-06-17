<?php

namespace Oza75\OrangeSMSChannel\Http\Requests;

use Exception;
use Oza75\OrangeSMSChannel\Http\OrangeSMSClientRequest;

class SMSDRCheckCallbackRequest extends OrangeSMSClientRequest
{
    /**
     * @var string
     */
    private string $id;

    /**
     * CheckSMSDRCallbackRequest constructor.
     * @param $id
     * @throws Exception
     */
    public function __construct($id)
    {
        if (! $id) {
            throw new Exception('Missing subscription id');
        }

        $this->id = $id;
    }

    /**
     * Http request method.
     *
     * @return string
     */
    public function method() : string
    {
        return 'GET';
    }

    /**
     * The uri for the current request.
     *
     * @return string
     */
    public function uri(): string
    {
        // '.urlencode($this->sender).'/
        return static::BASE_URI.'/smsmessaging/v1/outbound/subscriptions/'.$this->id;
    }

    /**
     * Http request options.
     *
     * @return array
     */
    public function options(): array
    {
        return [
            'headers' => ['Content-Type' => 'application/json']
        ];
    }
}
