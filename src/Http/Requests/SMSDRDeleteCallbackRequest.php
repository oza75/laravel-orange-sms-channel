<?php

namespace Oza75\OrangeSMSChannel\Http\Requests;

use Exception;
use Oza75\OrangeSMSChannel\Http\OrangeSMSClientRequest;

class SMSDRDeleteCallbackRequest extends OrangeSMSClientRequest
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $sender;

    /**
     * SMSDRDeleteCallbackRequest constructor.
     * @param $id
     * @param $sender
     * @throws Exception
     */
    public function __construct($id, $sender)
    {
        if (! $sender) {
            throw new Exception('Missing sender address');
        }

        if (! $id) {
            throw new Exception('Missing subscription id');
        }

        $this->sender = 'tel:'.$this->normalizePhoneNumber($sender);

        $this->id = $id;
    }

    /**
     * Http request method.
     *
     * @return string
     */
    public function method(): string
    {
        return 'DELETE';
    }

    /**
     * The uri for the current request.
     *
     * @return string
     */
    public function uri(): string
    {
        return static::BASE_URI.'/smsmessaging/v1/outbound/'.urlencode($this->sender).'/subscriptions/'.$this->id;
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
