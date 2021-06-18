# Laravel Orange SMS notification channel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/oza75/laravel-orange-sms-channel.svg?style=flat-square)](https://packagist.org/packages/oza75/laravel-orange-sms-channel)
[![Total Downloads](https://img.shields.io/packagist/dt/oza75/laravel-orange-sms-channel.svg?style=flat-square)](https://packagist.org/packages/oza75/laravel-orange-sms-channel)
![GitHub Actions](https://github.com/oza75/laravel-orange-sms-channel/actions/workflows/main.yml/badge.svg)

A Laravel Notification Channel to send sms notification to your users in the Middle East and Africa using Orange SMS.
More details here [https://developer.orange.com/apis/sms](https://developer.orange.com/apis/sms).

## Installation

You can install the package via composer:

```bash
composer require oza75/laravel-orange-sms-channel
```

You can publish configuration file using:

```bash
php  artisan vendor:publish --provider="Oza75\OrangeSMSChannel\OrangeSMSServiceProvider"
```

## Usage

First, you need to create an application on orange developer website. Go
to [https://developer.orange.com/myapps](https://developer.orange.com/myapps) and create a new application. Once you
created your application, you will get a `Client ID` and `Client Secret`. These credentials will be used to communicate with Orange
API. Now, you need to add the Orange SMS API to your application. Go
to [https://developer.orange.com/apis/sms](https://developer.orange.com/apis/sms) select your country and then click to
the `Use this API` button.

Finally, add a new service in your `config/service.php` file.

```php
// file: config/services.php

return [
    // ...others services
    
    'orange' => [
        'client_id' => env('ORANGE_CLIENT_ID'),
        'client_secret' => env('ORANGE_CLIENT_SECRET'),
    ]  
];
```

### Configure your notification
In your notification class, add the orange SMS Channel in the via method and create
a `toOrange` method.

```php
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Oza75\OrangeSMSChannel\OrangeMessage;
use Oza75\OrangeSMSChannel\OrangeSMSChannel;

class ConfirmationNotification extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [OrangeSMSChannel::class];
    }
    
    // ...others method here
    
    
     /**
     * Send sms using Orange API
     * 
     * @param mixed $notifiable
     * @return OrangeMessage
     */
    public function toOrange($notifiable):OrangeMessage
    {
        return (new OrangeMessage())
                ->to('+22600000000')
                ->from('+22600000000')
                ->text('Sample text'); 
    }
}
```
### Available Message methods

- `to` (the receiver phone number)
- `from` (the sender phone number)
- `text` (the actual text message)

### Configuration file

```php 
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
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email abouba181@gmail.com instead of using the issue tracker.

## Credits

- [Aboubacar OUATTARA](https://github.com/oza75)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
