<?php

class RecordTypeFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testmake()
    {
        $factory = new \Hostinger\RecordTypeFactory();
        $result  = $factory->make(DNS_MX);
        $this->assertNotNull($result);
        $this->assertInstanceOf(\Hostinger\RecordType\RecordType::class, $result);
    }
}
