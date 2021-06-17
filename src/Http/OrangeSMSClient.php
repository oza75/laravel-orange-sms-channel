<?php


namespace Oza75\OrangeSMSChannel\Http;


use Error;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
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
     * SMSCLient singleton instance.
     *
     * @var static
     */
    protected static OrangeSMSClient $instance;

    /**
     * SMSClient constructor.
     *
     * @throws Error
     */
    protected function __construct()
    {
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
    public function configure(): OrangeSMSClient
    {
        switch (count($options = func_get_args())) {
            case 0:
                break;

            case 1:
                $this->configureInstance($options[0]);
                break;

            case 2:
                $this->configureInstanceAssoc(
                    static::authorize($options[0], $options[1])
                );
                break;

            default:
                throw new InvalidArgumentException('invalid argument count');
                break;
        }

        return $this;
    }

    /**
     * Configure instance using options.
     *
     * @param  mixed  $options
     * @return $this
     */
    protected function configureInstance($options): OrangeSMSClient
    {
        if (is_string($options)) {
            $this->setToken($options)->setTokenExpiresIn(null);
        } elseif (is_array($options)) {
            $this->configureInstanceAssoc($options);
        }
    }

    /**
     * Configure instance using assoc array options.
     *
     * @param  array  $options
     * @return $this
     */
    protected function configureInstanceAssoc(array $options): OrangeSMSClient
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
    public static function authorize($clientID, $clientSecret): array
    {
        return json_decode(
            (new AuthorizationRequest($clientID, $clientSecret))->execute()->getBody(), true
        );
    }

    /**
     * Get the prepared singleton instance of the client.
     *
     * @return OrangeSMSClient
     * @throws GuzzleException
     */
    public static function getInstance(): OrangeSMSClient
    {
        if (! static::$instance) {
            static::$instance = new static();
        }

        return static::$instance->configure(...func_get_args());
    }
}