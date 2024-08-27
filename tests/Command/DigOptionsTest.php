<?php

namespace Hostinger\Dig\Tests\Command;

use Generator;
use Hostinger\Dig\Command\DigOptions;
use PHPUnit\Framework\TestCase;

class DigOptionsTest extends TestCase
{
    public static function optionsDataProvider(): Generator
    {
        yield 'empty' => [
            [
                null,
                null,
                null,
            ],
            [],
        ];

        yield 'all set' => [
            [
                'test.com',
                'ping.com',
                'NS',
            ],
            [
                'server' => escapeshellarg('@test.com'),
                'name' => '-q ' . escapeshellarg('ping.com'),
                'type' => '-t ' . escapeshellarg('NS'),
            ],
        ];

        yield 'some set' => [
            [
                null,
                null,
                'NS',
            ],
            [
                'type' => '-t ' . escapeshellarg('NS'),
            ],
        ];
    }

    /**
     * @dataProvider optionsDataProvider
     */
    public function testToCliOptions(array $args, array $expected): void
    {
        $query = new DigOptions(...$args);

        $this->assertEquals($expected, $query->toCliOptions());
    }
}
