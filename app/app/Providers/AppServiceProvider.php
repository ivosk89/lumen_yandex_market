<?php

namespace App\Providers;

use GuzzleHttp\Client;
use App\Services\YandexMarketService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(YandexMarketService::class, function () {
            return new YandexMarketService(
                new Client(
                    [
                        'base_uri' => env('YANDEX_MARKET_API_URL'),
                        'headers' => [
                            'Authorization' => sprintf(
                                'OAuth oauth_token="%s", oauth_client_id="%s"',
                                env('YANDEX_MARKET_TOKEN'),
                                env('YANDEX_CLIENT_ID')
                            ),
                            'Content-type' => 'application/json',
                            'Accept' => 'application/json',
                            'Accept-Language' => 'ru-RU',
                        ]
                    ]
                )
            );
        });
    }
}
