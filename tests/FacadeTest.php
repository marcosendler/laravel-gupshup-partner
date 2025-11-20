<?php

namespace GupshupPartner\Tests;

use GupshupPartner\Facades\GupshupPartner;
use GupshupPartner\GupshupPartnerClient;
use Illuminate\Support\Facades\Http;

class FacadeTest extends TestCase
{
    public function test_facade_resolves_to_client()
    {
        Http::fake([
            'https://partner.gupshup.io/partner/account/login' => Http::response(['token' => 'partner-token'], 200),
        ]);

        $client = GupshupPartner::getFacadeRoot();
        $this->assertInstanceOf(GupshupPartnerClient::class, $client);

        // convenience methods
        $apps = GupshupPartner::apps();
        $this->assertIsObject($apps);
    }
}
