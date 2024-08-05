<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Unit;

use Codeception\Attribute\DataProvider;
use Codeception\Test\Unit;
use PHPUnit\Framework\Attributes\CoversClass;
use Sweetchuck\Robo\PHPUnit\Test\UnitTester;
use Sweetchuck\Robo\PHPUnit\Utils;

#[CoversClass(Utils::class)]
class UtilsTest extends Unit
{

    protected UnitTester $tester;

    /**
     * @return array<string, dev-delimit-test-case-array>
     */
    public static function casesDelimit(): array
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

    #[DataProvider('casesDelimit')]
    public function testDelimit(string $expected, string $text, string $delimiter): void
    {
        $this->tester->assertSame($expected, Utils::delimit($text, $delimiter));
    }
}
