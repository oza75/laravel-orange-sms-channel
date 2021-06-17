<?php

namespace Oza75\OrangeSMSChannel\Http\Requests;

use Exception;
use Oza75\OrangeSMSChannel\Http\OrangeSMSClientRequest;

class OutboundSMSRequest extends OrangeSMSClientRequest
{
    /**
     * Request body
     *
     * @var array
     */
    protected array $body;

    /**
     * Recipient number
     *
     * @var string
     */
    protected string $sender;

    /**
     * OutboundSMSObjectRequest constructor.
     * @param $message
     * @param $recipientNumber
     * @param $senderNumber
     * @param $senderName
     * @throws Exception
     */
    public function __construct($message, $recipientNumber, $senderNumber, $senderName = null)
    {
        $this->throwsExceptionIfEmpty($recipientNumber, $senderNumber);

        $this->body = ['outboundSMSMessageRequest' => [
               'address' => 'tel:'.$this->normalizePhoneNumber($recipientNumber),
               'senderAddress' => $this->sender = 'tel:'.$this->normalizePhoneNumber($senderNumber),
               'outboundSMSTextMessage' => [ 'message' => $message ?: '']
           ]
        ];

        if ($senderName) {
            $this->body['outboundSMSMessageRequest']['senderName'] = urlencode($senderName);
        }
    }

    /**
     * @inherit
     *
     * @return string
     */
    public function method(): string
    {
        return 'POST';
    }

    /**
     * @inherit
     *
     * @return string
     * @throws Exception
     */
    public function uri(): string
    {
        return static::BASE_URI."/smsmessaging/v1/outbound/".urlencode($this->sender)."/requests";
    }

    /**
     * @inherit
     *
     * @return array
     */
    public function options(): array
    {
        return [
            'headers' => ["Content-Type" => "Application/json"],
            'body' => json_encode($this->body)
        ];
    }

    /**
     * @param $recipientNumber
     * @param $senderNumber
     * @throws Exception
     */
    private function throwsExceptionIfEmpty($recipientNumber, $senderNumber)
    {
        if (empty($senderNumber)) {
            throw new Exception('Missing Sender number');
        }

        if (empty($recipientNumber)) {
            throw new Exception('Missing Recipient number');
        }
    }
}
