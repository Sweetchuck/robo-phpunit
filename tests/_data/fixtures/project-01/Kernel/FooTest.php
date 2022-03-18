<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Fixtures\Project01\Kernel;

use PHPUnit\Framework\TestCase;

/**
 * @group Foo1
 * @group Foo3
 */
class FooTest extends TestCase
{
    public function testDummy(): void
    {
        $this->assertTrue(true);
    }
}
