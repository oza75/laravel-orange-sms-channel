<?php


namespace Oza75\OrangeSMSChannel\Contracts;


use GuzzleHttp\Exception\GuzzleException;
use Oza75\OrangeSMSChannel\Http\OrangeSMSClientRequest;
use Psr\Http\Message\StreamInterface;

interface OrangeSMSClient
{
    /**
     * Execute a request against the Api server
     *
     * @param OrangeSMSClientRequest $request
     * @param bool                   $decodeJson
     *
     * @return array|StreamInterface
     * @throws GuzzleException
     */
    public function executeRequest(OrangeSMSClientRequest $request, ?bool $decodeJson = true);

    /**
     * Get the client access token
     *
     * @param $clientID
     * @param $clientSecret
     *
     * @return array
     * @throws GuzzleException
     */
    public function authorize($clientID, $clientSecret): array;

    /**
     * @return void
     */
    public function boot(): void;

    /**
     * Configure the instance.
     *
     * @return \Oza75\OrangeSMSChannel\Http\OrangeSMSClient
     * @throws GuzzleException
     */
    public function configure(string $clientId, string $clientSecret): OrangeSMSClient;
}