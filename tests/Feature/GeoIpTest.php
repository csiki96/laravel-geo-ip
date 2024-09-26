<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Torann\GeoIP\Facades\GeoIP;

class GeoIpTest extends TestCase
{

    public function test_googlebot_access()
    {
        $response = $this->get('/', [
            'User-Agent' => 'Googlebot/2.1 (+http://www.google.com/bot.html)',
        ]);

        $response->assertStatus(200);
    }

    public function test_california_user_gets_404()
    {
        GeoIP::shouldReceive('getLocation')
            ->andReturn((object)[
                'ip' => '66.102.0.0',
                'iso_code' => 'US',
                'country' => 'United States',
                'city' => 'Mountain View',
                'state' => 'California',
                'state_name' => 'California',
                'postal_code' => '94043',
                'lat' => 37.4192,
                'lon' => -122.0574,
                'timezone' => 'America/Los_Angeles',
                'continent' => 'NA',
                'default' => false,
            ]);

        $response = $this->get('/', [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
        ]);

        $response->assertStatus(404);
    }

    public function test_non_california_user_access()
    {
        $response = $this->get('/', [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
            'X-Forwarded-For' => '8.8.8.8',
        ]);

        $response->assertStatus(200);
    }
}
