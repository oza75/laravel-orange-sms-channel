<?php


namespace Oza75\OrangeSMSChannel;


use Illuminate\Support\ServiceProvider;
use Oza75\OrangeSMSChannel\Contracts\OrangeSMSBridge;
use Oza75\OrangeSMSChannel\Contracts\OrangeSMSClient;
use Oza75\OrangeSMSChannel\Http\OrangeSMSClient as HTTPSMSClient;

class OrangeSMSServiceProvider extends ServiceProvider
{

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton(OrangeSMSClient::class, function ($app) {
            $credentials = $app['config']->get('services.orange.sms');

            return HTTPSMSClient::getInstance($credentials['client_id'], $credentials['client_secret']);
        });

        $this->app->singleton(OrangeSMSBridge::class, function ($app) {
            return new OrangeSMS($app->make(OrangeSMSClient::class));
        });
    }

}