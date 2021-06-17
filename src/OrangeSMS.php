<?php


namespace Oza75\OrangeSMSChannel;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Oza75\OrangeSMSChannel\Contracts\OrangeSMSClient as SMSClient;
use Oza75\OrangeSMSChannel\Contracts\OrangeSMSBridge;
use Oza75\OrangeSMSChannel\Http\Requests\ContractsRequest;
use Oza75\OrangeSMSChannel\Http\Requests\OrdersHistoryRequest;
use Oza75\OrangeSMSChannel\Http\Requests\OutboundSMSRequest;
use Oza75\OrangeSMSChannel\Http\Requests\SMSDRCheckCallbackRequest;
use Oza75\OrangeSMSChannel\Http\Requests\SMSDRDeleteCallbackRequest;
use Oza75\OrangeSMSChannel\Http\Requests\SMSDRRegisterCallbackRequest;
use Oza75\OrangeSMSChannel\Http\Requests\StatisticsRequest;

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
     * @param string $number
     * @param string|null $name
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
        return $this->client->executeRequest(
            new OutboundSMSRequest(
                $this->message,
                $this->recipientNumber,
                $this->senderNumber,
                $this->senderName
            )
        );
    }

    /**
     * Get SMS contracts.
     *
     * @param string|null $country
     * @return array
     * @throws GuzzleException
     */
    public function balance(string $country = null): array
    {
        return $this->client->executeRequest(
            new ContractsRequest($country)
        );
    }

    /**
     * Get SMS orders history.
     *
     * @param string|null $country
     * @return array
     * @throws GuzzleException
     */
    public function ordersHistory(string $country = null): array
    {
        return $this->client->executeRequest(
            new OrdersHistoryRequest($country)
        );
    }

    /**
     * Get SMS statistics.
     *
     * @param string|null $country
     * @param string|null $appID
     * @return array
     * @throws GuzzleException
     */
    public function statistics(string $country = null, string $appID = null): array
    {
        return $this->client->executeRequest(
            new StatisticsRequest($country, $appID)
        );
    }

    /**
     * Set the SMS DR notification endpoint.
     *
     * @param string $url
     * @param string|null $sender
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function setDeliveryReceiptNotificationUrl(string $url, ?string $sender = null): array
    {
        return $this->client->executeRequest(
            new SMSDRRegisterCallbackRequest($url, $sender ?: $this->senderNumber)
        );
    }

    /**
     * Check the SMS DR notification endpoint.
     *
     * @param string $id
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function checkDeliveryReceiptNotificationUrl(string $id): array
    {
        return $this->client->executeRequest(
            new SMSDRCheckCallbackRequest($id)
        );
    }

    /**
     * Delete the SMS DR notification endpoint.
     *
     * @param string $id
     * @param string|null $sender
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function deleteDeliveryReceiptNotificationUrl(string $id, ?string $sender = null): array
    {
        return $this->client->executeRequest(
            new SMSDRDeleteCallbackRequest($id, $sender ?: $this->senderNumber)
        );
    }
}