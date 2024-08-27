<?php

namespace Hostinger\Dig\Tests\Command;

use Generator;
use Hostinger\Dig\Command\DigQuery;
use PHPUnit\Framework\TestCase;

class DigQueryTest extends TestCase
{
    public static function queryDataProvider(): Generator
    {
        yield 'empty' => [
            [
                null,
                null,
                null,
                null,
            ],
            [],
        ];

        yield 'all set' => [
            [
                true,
                true,
                true,
                10,
            ],
            [
                'all' => escapeshellarg('+all'),
                'answer' => escapeshellarg('+answer'),
                'authority' => escapeshellarg('+authority'),
                'time' => escapeshellarg('+time=10'),
            ],
        ];

        yield 'all set to false' => [
            [
                false,
                false,
                false,
                5,
            ],
            [
                'all' => escapeshellarg('+noall'),
                'answer' => escapeshellarg('+noanswer'),
                'authority' => escapeshellarg('+noauthority'),
                'time' => escapeshellarg('+time=5'),
            ],
        ];
    }

    /**
     * @dataProvider queryDataProvider
     */
    public function testToCliQuery(array $args, array $expected): void
    {
        $query = new DigQuery(...$args);

        $this->assertEquals($expected, $query->toCliQuery());
    }
}
