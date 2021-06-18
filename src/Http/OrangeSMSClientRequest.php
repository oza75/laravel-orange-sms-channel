<?php


namespace Oza75\OrangeSMSChannel\Http;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;

abstract class OrangeSMSClientRequest
{
    /**
     * base api uri.
     */
    const BASE_URI = 'https://api.orange.com';

    /**
     * Describes the SSL certificate verification behavior of a request.
     */
    protected static bool $verify_ssl = true;

    /**
     * @var Client
     */
    protected static Client $httpClient;

    /**
     * Http request method.
     *
     * @return string
     */
    abstract public function method(): string;

    /**
     * The uri for the current request.
     *
     * @return string
     */
    abstract public function uri(): string;

    /**
     * Http request options.
     *
     * @return array
     */
    public function options(): array
    {
        return [];
    }

    /**
     * Set the SSL certificate verification behavior of a request.
     *
     * @param bool|string $value
     * @return void
     */
    public static function verify($value)
    {
        if (is_bool($value) || is_string($value)) {
            static::$verify_ssl = $value;
        }
    }

    /**
     * Set the http client.
     *
     * @param Client $client
     */
    public static function setHttpClient(Client $client)
    {
        static::$httpClient = $client;
    }

    /**
     * Get the http client.
     *
     * @return Client
     */
    public static function getHttpClient(): Client
    {
//        if (static::$httpClient && static::$httpClient->getConfig('verify') === static::$verify_ssl) {
//            return static::$httpClient;
//        }

        return new Client(['http_errors' => false, 'verify' => static::$verify_ssl]);
    }

    /**
     * Execute the request.
     *
     * @param array|null $options
     * @return ResponseInterface
     * @throws GuzzleException
     */
    final public function execute(?array $options = null): ResponseInterface
    {
        return $this
            ->getHttpClient()
            ->request(
                $this->method(),
                $this->uri(),
                $options ?: $this->options()
            );
    }

    /**
     * Normalize phone number.
     *
     * @param string $phone
     * @return string
     */
    protected function normalizePhoneNumber(string $phone): string
    {
        if (Str::startsWith('+', $phone)) {
            return $phone;
        }

        if ($country_code = config('orange-sms.country_code')) {
            return $country_code . $phone;
        }

        return '+'.$phone;
    }
}