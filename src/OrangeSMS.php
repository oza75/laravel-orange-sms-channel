<?php


namespace Oza75\OrangeSMSChannel;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Oza75\OrangeSMSChannel\Contracts\OrangeSMSClient as SMSClient;
use Oza75\OrangeSMSChannel\Contracts\OrangeSMSBridge;
use Oza75\OrangeSMSChannel\Http\Requests\OutboundSMSRequest;

class OrangeSMS implements OrangeSMSBridge
{
    /**
     * @var string
     */
    protected string $recipientNumber;
    /**
     * @var string
     */
    protected string $senderNumber;

    /**
     * @var string|null
     */
    protected ?string $senderName;

    /**
     * @var string
     */
    protected string $message;

    /**
     * @var SMSClient
     */
    protected SMSClient $client;

    /**
     * Outbound SMS Object constructor.
     *
     * @param SMSClient $client
     */
    public function __construct(SMSClient $client)
    {
        $this->client = $client;
    }

    /**
     * Set SMS client.
     *
     * @param SMSClient $client
     *
     * @return $this
     */
    public function setClient(SMSClient $client): OrangeSMS
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Set SMS recipient.
     *
     * @param string $recipientNumber
     *
     * @return $this
     */
    public function to(string $recipientNumber): self
    {
        $this->recipientNumber = $recipientNumber;

        return $this;
    }

    /**
     * set SMS sender details.
     *
     * @param string      $number
     * @param string|null $name
     *
     * @return $this
     */
    public function from(string $number, string $name = null): self
    {
        $this->senderNumber = $number;

        $this->senderName = $name;

        return $this;
    }

    /**
     * Set SMS message.
     *
     * @param string $message
     *
     * @return $this
     */
    public function message(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Send SMS.
     *
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function send(): array
    {
        $this->client->boot();

        return $this->client->executeRequest(
            new OutboundSMSRequest(
                $this->message,
                $this->recipientNumber,
                $this->senderNumber,
                $this->senderName
            )
        );
    }
}