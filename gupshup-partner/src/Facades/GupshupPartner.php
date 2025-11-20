<?php

namespace GupshupPartner\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Services\GupshupPartner\AppManagement apps()
 * @method static \App\Services\GupshupPartner\TemplateManagement templates()
 * @method static \App\Services\GupshupPartner\MessageManagement messages()
 * @method static \App\Services\GupshupPartner\AnalyticsManagement analytics()
 * @method static \App\Services\GupshupPartner\WalletManagement wallet()
 * @method static \App\Services\GupshupPartner\FlowManagement flows()
 * @method static string getPartnerToken(bool $forceRefresh = false)
 * @method static mixed get(string $endpoint, array $params = [])
 * @method static mixed post(string $endpoint, array $data = [])
 * @method static mixed put(string $endpoint, array $data = [])
 * @method static mixed delete(string $endpoint, array $data = [])
 *
 * @see \App\Services\GupshupPartner\GupshupPartnerClient
 */
class GupshupPartner extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'gupshup.partner';
    }
}
