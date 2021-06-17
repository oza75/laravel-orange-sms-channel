<?php

namespace Oza75\OrangeSMSChannel\Http\Requests;


use Exception;
use Oza75\OrangeSMSChannel\Http\OrangeSMSClientRequest;

class SMSDRRegisterCallbackRequest extends OrangeSMSClientRequest
{
    /**
     * @var array
     */
    protected array $body;
    /**
     * @var string
     */
    private string $senderAddress;

    /**
     * RegisterSMSDRCallbackRequest constructor.
     *
     * @param $callbackUri
     * @param $senderAddress
     * @throws \Exception
     */
    public function __construct($callbackUri, $senderAddress)
    {
        $this->enforceHttpSecureProtocol($callbackUri);

        if (!$senderAddress) {
            throw new Exception('Missing sender address');
        }

        $this->senderAddress = 'tel:' . $this->normalizePhoneNumber($senderAddress);

        $this->body = [
            "deliveryReceiptSubscription" => [
                "callbackReference" => [
                    "notifyURL" => $callbackUri
                ]
            ]
        ];
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
        return static::BASE_URI . '/smsmessaging/v1/outbound/' . urlencode($this->senderAddress) . '/subscriptions';
    }

    /**
     * Http request options.
     *
     * @return array
     */
    public function options(): array
    {
        return [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($this->body)
        ];
    }

    /**
     * @param $callbackUri
     */
    protected function enforceHttpSecureProtocol($callbackUri)
    {
        if (substr($callbackUri, 0, strlen('https://')) !== 'https://') {
            throw new \InvalidArgumentException(
                "Url callback protocol must be secured and starts with: 'https://'"
            );
        }
    }
}
