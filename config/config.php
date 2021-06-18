<?php

return [
    /****
     * The country code that must be prepend to all phone number.
     * If the phone number already start with the `+`(plus) symbol,
     * the country code will not be applied.
     */
    'country_code' => null,

    /**
     * You may wish for all SMS sent by your application to be sent from
     * the same phone number. Here, you may specify a name and a phone number that is
     * used globally for all SMS that are sent by your application.
     */
    'from' => [
        'phone_number' => null,
        'name' => env('APP_NAME')
    ]
];