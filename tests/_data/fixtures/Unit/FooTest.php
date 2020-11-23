<?php

namespace Sweetchuck\Robo\PHPUnit\TestsFixtures\Unit;

use PHPUnit\Framework\TestCase;

/**
 * @group Foo1
 * @group Foo2
 */
class FooTest extends TestCase
{
    public function testDummy()
    {
        $this->assertTrue(true);
    }
}
