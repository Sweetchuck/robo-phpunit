<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Fixtures\Project01\Unit;

use PHPUnit\Framework\TestCase;

/**
 * @group Foo1
 * @group Foo2
 */
class FooTest extends TestCase
{
    public function testDummy(): void
    {
        $this->assertTrue(true);
    }

    /**
     * @return array<string, mixed>
     */
    public function casesDummy02(): array
    {
        return [
            'a' => [true],
            'b' => [true],
        ];
    }

    /**
     * @dataProvider casesDummy02
     */
    public function testDummy02(bool $expected): void
    {
        $this->assertSame($expected, true);
    }
}
