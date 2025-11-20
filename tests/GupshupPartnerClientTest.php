<?php

namespace GupshupPartner\Tests;

use GupshupPartner\GupshupPartnerClient;
use GupshupPartner\Exceptions\GupshupPartnerException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GupshupPartnerClientTest extends TestCase
{
    public function test_can_get_partner_token_and_cache_it()
    {
        Http::fake([
            'https://partner.gupshup.io/partner/account/login' => Http::response(['token' => 'partner-token'], 200),
        ]);

        Cache::flush();

        $client = new GupshupPartnerClient('test@example.com', 'secret');

        $token = $client->getPartnerToken();

        $this->assertEquals('partner-token', $token);

        // token cached
        $this->assertTrue(Cache::has('gupshup_partner_token_' . md5('test@example.com')));
    }

    public function test_get_token_throws_exception_on_http_error()
    {
        Http::fake([
            'https://partner.gupshup.io/partner/account/login' => Http::response(['error' => 'invalid'], 401),
        ]);

        $this->expectException(GupshupPartnerException::class);

        $client = new GupshupPartnerClient('test@example.com', 'secret');
        $client->getPartnerToken(true);
    }

    public function test_apps_list_calls_endpoint()
    {
        Http::fake([
            'https://partner.gupshup.io/partner/account/api/partnerApps' => Http::response(['partnerAppsList' => []], 200),
            'https://partner.gupshup.io/partner/account/login' => Http::response(['token' => 'partner-token'], 200),
        ]);

        $client = new GupshupPartnerClient('test@example.com', 'secret');

        $result = $client->apps()->list();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('partnerAppsList', $result);
    }

    public function test_get_app_token_and_cache()
    {
        $appId = 'app-123';

        Http::fake([
            "https://partner.gupshup.io/partner/app/{$appId}/token/" => Http::response(['token' => ['token' => 'app_token']], 200),
            'https://partner.gupshup.io/partner/account/login' => Http::response(['token' => 'partner-token'], 200),
        ]);

        Cache::flush();

        $client = new GupshupPartnerClient('test@example.com', 'secret');
        $appToken = $client->apps()->getToken($appId);

        $this->assertEquals('app_token', $appToken);
        $this->assertTrue(Cache::has('gupshup_app_token_' . $appId));
    }
}
