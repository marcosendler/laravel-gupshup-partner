<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Gupshup Partner API Configuration
    |--------------------------------------------------------------------------
    |
    | Configurações para integração com as APIs de Parceiro do Gupshup
    |
    */

    'partner' => [
        'email' => env('GUPSHUP_PARTNER_EMAIL'),
        'password' => env('GUPSHUP_PARTNER_PASSWORD'),
        'base_url' => env('GUPSHUP_PARTNER_BASE_URL', 'https://partner.gupshup.io'),
        'token_expiry_hours' => env('GUPSHUP_PARTNER_TOKEN_EXPIRY', 24),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default App Configuration
    |--------------------------------------------------------------------------
    |
    | Configuração do app padrão (opcional)
    |
    */

    'default_app' => [
        'id' => env('GUPSHUP_DEFAULT_APP_ID'),
        'name' => env('GUPSHUP_DEFAULT_APP_NAME'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Configurações de cache para tokens e outras informações
    |
    */

    'cache' => [
        'enabled' => env('GUPSHUP_CACHE_ENABLED', true),
        'prefix' => env('GUPSHUP_CACHE_PREFIX', 'gupshup_'),
        'ttl' => env('GUPSHUP_CACHE_TTL', 3600), // segundos
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Configurações de log para debug e auditoria
    |
    */

    'logging' => [
        'enabled' => env('GUPSHUP_LOGGING_ENABLED', false),
        'channel' => env('GUPSHUP_LOG_CHANNEL', 'daily'),
        'level' => env('GUPSHUP_LOG_LEVEL', 'info'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Retry Configuration
    |--------------------------------------------------------------------------
    |
    | Configurações para retry automático em caso de falha
    |
    */

    'retry' => [
        'enabled' => env('GUPSHUP_RETRY_ENABLED', true),
        'times' => env('GUPSHUP_RETRY_TIMES', 3),
        'sleep' => env('GUPSHUP_RETRY_SLEEP', 100), // milissegundos
    ],

    /*
    |--------------------------------------------------------------------------
    | Timeout Configuration
    |--------------------------------------------------------------------------
    |
    | Configurações de timeout para requisições HTTP
    |
    */

    'timeout' => [
        'connection' => env('GUPSHUP_CONNECTION_TIMEOUT', 10),
        'request' => env('GUPSHUP_REQUEST_TIMEOUT', 30),
    ],

];
