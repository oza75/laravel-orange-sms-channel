<?php


namespace Oza75\OrangeSMSChannel\Http;


use GuzzleHttp\Exception\GuzzleException;
use Oza75\OrangeSMSChannel\Contracts\OrangeSMSClient as ClientContract;
use Oza75\OrangeSMSChannel\Http\Requests\AuthorizationRequest;
use Psr\Http\Message\StreamInterface;

class OrangeSMSClient implements ClientContract
{
    /**
     * Access token.
     *
     * @var string|mixed
     */
    protected string $token;

    /**
     * Expires time.
     *
     * @var string
     */
    protected string $expiresIn;

    /**
     * SMSClient constructor.
     *
     * @throws GuzzleException
     */
    public function __construct(string $clientId, string $clientSecret)
    {
        $this->configure($clientId, $clientSecret);
    }

    /**
     * Set the access token.
     *
     * @param $token
     * @return $this
     */
    public function setToken($token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get the access token.
     *
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the expires_in in seconds
     *
     * @param string $expiresIn
     * @return $this
     */
    public function setTokenExpiresIn(string $expiresIn): OrangeSMSClient
    {
        $this->expiresIn = $expiresIn;

        return $this;
    }

    /**
     * Get the expire_in in seconds
     *
     * @return string
     */
    public function getTokenExpiresIn(): string
    {
        return $this->expiresIn;
    }

    /**
     * Configure the instance.
     *
     * @return $this
     * @throws GuzzleException
     */
    public function configure(string $clientId, string $clientSecret): OrangeSMSClient
    {
        $this->configureToken(static::authorize($clientId, $clientSecret));

        return $this;
    }

    /**
     * Configure instance using assoc array options.
     *
     * @param  array  $options
     * @return $this
     */
    protected function configureToken(array $options): OrangeSMSClient
    {
        if (array_key_exists('access_token', $options)) {
            $this->setToken($options['access_token']);
        }

        if (array_key_exists('expires_in', $options)) {
            $this->setTokenExpiresIn($options['expires_in']);
        }

        return $this;
    }

    /**
     * Execute a request against the Api server
     *
     * @param OrangeSMSClientRequest $request
     * @param bool $decodeJson
     * @return array|StreamInterface
     * @throws GuzzleException
     */
    public function executeRequest(OrangeSMSClientRequest $request, ?bool $decodeJson = true)
    {
        $options = $request->options();

        if (! isset($options['headers']["Authorization"])) {
            $options['headers']["Authorization"] = "Bearer ". $this->getToken();
        }

        $response = $request->execute($options)->getBody();

        return $decodeJson ? json_decode($response, true) : $response;
    }

    /**
     * Get the client access token
     *
     * @param $clientID
     * @param $clientSecret
     * @return array
     * @throws GuzzleException
     */
    public function authorize($clientID, $clientSecret): array
    {
        return json_decode(
            (new AuthorizationRequest($clientID, $clientSecret))->execute()->getBody(), true
        );
    }
}