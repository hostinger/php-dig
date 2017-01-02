<?php

class ClientTest extends \PHPUnit\Framework\TestCase
{
    public function testExecEnabled()
    {
        $client = new Hostinger\DigClient();
        $result = $client->execEnabled();
        $this->assertTrue($result, $result);
    }

    public function domainsAndTypesProvider()
    {
        return [
            ['hostingermail.com', DNS_MX],
            ['ghs.google.com', DNS_CNAME],
        ];
    }

    /**
     * @dataProvider domainsAndTypesProvider
     */
    public function testgetRecords($domain, $type)
    {
        $client = new Hostinger\DigClient();
        $result = $client->getRecord($domain, $type);
        $this->assertTrue(is_array($result));
        $this->assertArrayHasKey(0, $result);

        $expected = dns_get_record($domain, $type);

        $this->assertEmpty(array_diff_key($result, $expected));
    }
}
