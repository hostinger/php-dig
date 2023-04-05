<?php

declare(strict_types=1);

namespace Hostinger\Dig\Tests;

use Hostinger\Dig\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function domainsAndTypesProvider(): array
    {
        return [
            ['hostinger.com', DNS_A],
            ['hostinger.com', DNS_AAAA],
            ['hostingermail.com', DNS_MX],
            ['hostingermail.com', DNS_NS],
        ];
    }

    /**
     * @dataProvider domainsAndTypesProvider
     */
    public function testGetRecords($domain, $type): void
    {
        $client = new Client();
        $result = $client->getRecord($domain, $type);
        $this->assertArrayHasKey(0, $result);

        $expected = dns_get_record($domain, $type);

        $resultExpecteKeySame = array_diff_key($expected[0], $result[0]);
        $this->assertEmpty($resultExpecteKeySame, json_encode($resultExpecteKeySame));

        $this->assertEquals($expected[0]['host'], $result[0]['host']);
        $this->assertEquals($expected[0]['class'], $result[0]['class']);
        $this->assertEquals($expected[0]['type'], $result[0]['type']);
    }

    public function testFallbacksToCustom(): void
    {
        $customRecord = [
            'type' => 'CUSTOM',
        ];
        $client = new Client(function () use ($customRecord) {
            return [$customRecord];
        });

        $result = $client->getRecord('hostinger.com', DNS_CAA);
        $this->assertCount(1, $result);
        $this->assertEquals('CUSTOM', $result[0]['type']);
    }
}
