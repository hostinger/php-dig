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
            ['hostingermail.com', DNS_NS],
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

        $resultExpecteKeySame = array_diff_key($expected[0], $result[0]);
        $this->assertEmpty($resultExpecteKeySame, json_encode($resultExpecteKeySame));

        $this->assertEquals($expected[0]['host'], $result[0]['host']);
        $this->assertEquals($expected[0]['class'], $result[0]['class']);
        $this->assertEquals($expected[0]['type'], $result[0]['type']);
    }
}
