<?php

namespace GupshupPartner;

use Illuminate\Support\ServiceProvider;
use GupshupPartner\GupshupPartnerClient;

class GupshupPartnerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Registra o cliente como singleton
        $this->app->singleton(GupshupPartnerClient::class, function ($app) {
            return new GupshupPartnerClient(
                config('gupshup.partner.email'),
                config('gupshup.partner.password')
            );
        });

        // Registra um alias
        $this->app->alias(GupshupPartnerClient::class, 'gupshup.partner');

        // Merge configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/gupshup.php',
            'gupshup'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publica o arquivo de configuração
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/gupshup.php' => config_path('gupshup.php'),
            ], 'gupshup-config');
        }
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            GupshupPartnerClient::class,
            'gupshup.partner',
        ];
    }
}
