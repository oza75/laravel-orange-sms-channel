<?php

namespace Oza75\OrangeSMSChannel\Http\Requests;

use Mediumart\Orange\SMS\Http\SMSClientRequest;
use Oza75\OrangeSMSChannel\Http\OrangeSMSClientRequest;

class StatisticsRequest extends OrangeSMSClientRequest
{
    /**
     * International country code.
     *
     * @see http://fr.wikipedia.org/wiki/ISO_3166-1#Table_de_codage
     * @var string|null
     */
    protected ?string $countryCode;

    /**
     * Api app ID.
     *
     * @var string|null
     */
    protected ?string $appID;

    /**
     * SMSAdminStatsRequest constructor.
     *
     * @param string|null $countryCode
     * @param string|null $appID
     */
    public function __construct(?string $countryCode = null, ?string $appID = null)
    {
        $this->countryCode = $countryCode;
        $this->appID = $appID;
    }

    /**
     * @inherit
     *
     * @return string
     */
    public function method(): string
    {
        return 'GET';
    }

    /**
     * @inherit
     *
     * @return string
     */
    public function uri(): string
    {
        $filters = $this->queryFilters();

        $uri = static::BASE_URI . '/sms/admin/v1/statistics';

        return count($filters) ? $uri.'?'.http_build_query($filters) : $uri;
    }

    /**
     * @return array
     */
    private function queryFilters(): array
    {
        $filters = [];

        if ($this->countryCode) {
            $filters['country'] = $this->countryCode;
        }

        if ($this->appID) {
            $filters['appid'] = $this->appID;
        }

        return $filters;
    }
}
