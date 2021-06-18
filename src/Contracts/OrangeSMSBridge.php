<?php


namespace Oza75\OrangeSMSChannel\Contracts;


use Exception;
use GuzzleHttp\Exception\GuzzleException;

interface OrangeSMSBridge
{
    /**
     * Set SMS recipient.
     *
     * @param string $recipientNumber
     * @return $this
     */
    public function to(string $recipientNumber): self;

    /**
     * set SMS sender details.
     *
     * @param string $number
     * @param string|null $name
     * @return $this
     */
    public function from(string $number, string $name = null): self;

    /**
     * Set SMS message.
     *
     * @param string $message
     * @return $this
     */
    public function message(string $message): self;

    /**
     * Send SMS.
     *
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function send(): array;
}