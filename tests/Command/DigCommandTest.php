<?php

namespace Hostinger\Dig\Tests\Command;

use Generator;
use Hostinger\Dig\Command\DigCommand;
use Hostinger\Dig\Command\DigOptions;
use Hostinger\Dig\Command\DigQuery;
use PHPUnit\Framework\TestCase;

class DigCommandTest extends TestCase
{
    public static function dataProvider(): Generator
    {
        yield 'basic' => [
            [
                'test.com',
                'NS',
            ],
            "dig 'test.com' 'NS'",
        ];

        yield 'with options' => [
            [
                'test.com',
                'NS',
                new DigOptions(
                    'ping.com',
                ),
            ],
            "dig '@ping.com' 'test.com' 'NS'",
        ];

        yield 'with options and query' => [
            [
                'test.com',
                'NS',
                new DigOptions(
                    'ping.com',
                ),
                new DigQuery(
                    false,
                    true,
                    false,
                    10,
                ),
            ],
            "dig '@ping.com' '+noall' '+answer' '+noauthority' '+time=10' 'test.com' 'NS'",
        ];

        yield 'escapes args' => [
            [
                'test.com && exit 1',
                'NS && ls -la',
            ],
            "dig 'test.com && exit 1' 'NS && ls -la'",
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testToCliCommand(array $args, string $expected): void
    {
        $command = new DigCommand(...$args);

        $this->assertEquals($expected, $command->toCliCommand());
    }
}
