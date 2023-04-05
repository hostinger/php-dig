<?php

declare(strict_types=1);

namespace Hostinger\Dig\Tests;

use Hostinger\Dig\RecordType\A;
use Hostinger\Dig\RecordType\AAAA;
use Hostinger\Dig\RecordType\Cname;
use Hostinger\Dig\RecordType\Mx;
use Hostinger\Dig\RecordType\Ns;
use Hostinger\Dig\RecordTypeFactory;
use PHPUnit\Framework\TestCase;

class RecordTypeFactoryTest extends TestCase
{
    public function recordTypeProvider(): array
    {
        return [
            [DNS_A, A::class],
            [DNS_AAAA, AAAA::class],
            [DNS_MX, Mx::class],
            [DNS_CNAME, Cname::class],
            [DNS_NS, Ns::class],
        ];
    }

    /**
     * @dataProvider recordTypeProvider
     */
    public function testMake(int $type, string $expectedClass): void
    {
        $factory = new RecordTypeFactory();
        $result = $factory->make($type);
        $this->assertNotNull($result);
        $this->assertInstanceOf($expectedClass, $result);
    }

    public function testMakesUnknown(): void
    {
        $factory = new RecordTypeFactory();
        $result = $factory->make(0);
        $this->assertNull($result);
    }
}
