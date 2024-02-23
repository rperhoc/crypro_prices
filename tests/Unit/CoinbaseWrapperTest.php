<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\CoinbaseWrapper;
use Illuminate\Support\Facades\Http;

class CoinbaseWrapperTest extends TestCase
{
    /**
     * @dataProvider currencyProvider
     */
    public function test_exchange_rate_request($cryptoCode, $fiatCode, $price = 'spot')
    {
        $endpoint = sprintf('https://api.coinbase.com/v2/' . 'prices/%s-%s/%s', $cryptoCode, $fiatCode, $price);
        $exchangeRate = CoinbaseWrapper::exchangeRate($cryptoCode, $fiatCode, $price);
        $amount = 123.45;
        
        Http::fake([
            $endpoint => Http::response([
                'data' => [
                    'amount' => $amount
                ],
            ], 200)
        ]);

        $this->assertEquals($amount, $exchangeRate);
    }

    public static function currencyProvider()
    {
        return [
            ['BTC', 'USD'],
            ['ETH', 'EUR'],
            ['ADA', 'AED']
        ];
    }

    /**
     * @dataProvider errorProvider
     */
    public function test_network_error_when_accessing_api_endpoint($error)
    {
        Http::fake([
            'https://api.coinbase.com/v2/currencies/crypto' => Http::response(null, $error)
        ]);
        
        try {
            CoinbaseWrapper::cryptoData();
        } catch (\Exception $e) {
            $this->assertInstanceOf(\Exception::class, $e);
            $this->assertEquals('API request failed: ' . $error, $e->getMessage());
            return;
        }
        $this->fail('Exception not thrown.');
        
    }

    public static function errorProvider()
    {
        return [
            [400], [401], [403], [404]
        ];
    }

}
