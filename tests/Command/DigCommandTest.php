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
            "dig -t 'NS' -q 'test.com'",
        ];

        yield 'with options' => [
            [
                'test.com',
                'NS',
                new DigOptions(
                    'ping.com',
                ),
            ],
            "dig '@ping.com' -t 'NS' -q 'test.com'",
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
            "dig '@ping.com' '+noall' '+answer' '+noauthority' '+time=10' -t 'NS' -q 'test.com'",
        ];

        yield 'escapes args' => [
            [
                'test.com && exit 1',
                'NS && ls -la',
            ],
            "dig -t 'NS && ls -la' -q 'test.com && exit 1'",
        ];

        yield 'option-like name -f-' => [
            [
                '-f-',
                'A',
            ],
            "dig -t 'A' -q '-f-'",
        ];

        yield 'option-like name +foo' => [
            [
                '+foo',
                'A',
            ],
            "dig -t 'A' -q '+foo'",
        ];

        yield 'option-like name -fx' => [
            [
                '-fx',
                'A',
            ],
            "dig -t 'A' -q '-fx'",
        ];

        yield 'option-like name @x' => [
            [
                '@x',
                'A',
            ],
            "dig -t 'A' -q '@x'",
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
