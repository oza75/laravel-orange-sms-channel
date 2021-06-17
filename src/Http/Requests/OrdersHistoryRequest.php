<?php

namespace Oza75\OrangeSMSChannel\Http\Requests;

use Oza75\OrangeSMSChannel\Http\OrangeSMSClientRequest;

class OrdersHistoryRequest extends OrangeSMSClientRequest
{
    /**
     * International country code.
     *
     * @see http://fr.wikipedia.org/wiki/ISO_3166-1#Table_de_codage
     * @var string|null
     */
    protected ?string $countryCode;

    /**
     * SMSAdminPurchasedOrdersRequest constructor.
     *
     * @param string|null $countryCode
     */
    public function __construct(?string $countryCode = null)
    {
        $this->countryCode = $countryCode;
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
        $uri = static::BASE_URI . '/sms/admin/v1/purchaseorders';

        return $this->countryCode ? $uri.'?country='.$this->countryCode : $uri;
    }
}
