<?php


namespace Oza75\OrangeSMSChannel;


use Illuminate\Support\ServiceProvider;
use Oza75\OrangeSMSChannel\Contracts\OrangeSMSBridge;
use Oza75\OrangeSMSChannel\Contracts\OrangeSMSClient;
use Oza75\OrangeSMSChannel\Http\OrangeSMSClient as HTTPSMSClient;

class OrangeSMSServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('orange-sms.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'orange-sms');

        $this->app->singleton(OrangeSMSClient::class, function ($app) {
            $credentials = $app['config']->get('services.orange');

            return new HTTPSMSClient($credentials['client_id'], $credentials['client_secret']);
        });

        $this->app->singleton(OrangeSMSBridge::class, function ($app) {
            return new OrangeSMS($app->make(OrangeSMSClient::class));
        });
    }

}