<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Unit;

use Codeception\Test\Unit;
use Sweetchuck\Robo\PHPUnit\Test\UnitTester;
use Sweetchuck\Robo\PHPUnit\Utils;

/**
 * @covers \Sweetchuck\Robo\PHPUnit\Utils
 */
class UtilsTest extends Unit
{

    protected UnitTester $tester;

    public function casesDelimit(): array
    {
        return [
            'empty' => [
                '',
                '',
                '-',
            ],
            'basic' => [
                'foo-bar',
                'fooBar',
                '-',
            ],
        ];
    }

    /**
     * @dataProvider casesDelimit
     */
    public function testDelimit(string $expected, string $text, string $delimiter): void
    {
        $this->tester->assertSame($expected, Utils::delimit($text, $delimiter));
    }
}
