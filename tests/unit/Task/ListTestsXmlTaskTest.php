<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Unit\Task;

use PHPUnit\Framework\Attributes\CoversClass;
use Sweetchuck\Robo\PHPUnit\Task\BaseCliTask;
use Sweetchuck\Robo\PHPUnit\Task\BaseTask;
use Sweetchuck\Robo\PHPUnit\Task\ListTestsXmlTask;

#[CoversClass(ListTestsXmlTask::class)]
#[CoversClass(BaseCliTask::class)]
#[CoversClass(BaseTask::class)]
class ListTestsXmlTaskTest extends BaseCliTaskTestBase
{

    protected function createTaskInstance(): ListTestsXmlTask
    {
        return new ListTestsXmlTask();
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesGetCommand(): array
    {
        return [
            'basic' => [
                "php 'vendor/bin/phpunit' --list-tests-xml='php://stdout'",
                [],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesRunSuccess(): array
    {
        $xml01 = [
            '<?xml version="1.0"?>',
            '<tests>',
            '  <testCaseClass name="Sweetchuck\Robo\PHPUnit\TestsFixtures\Project01\Unit\FooTest">',
            '    <testCaseMethod name="testDummy" groups="Foo1,Foo2"/>',
            '    <testCaseMethod name="testDummy02" groups="Foo1,Foo2" dataSet="&quot;a&quot;"/>',
            '    <testCaseMethod name="testDummy02" groups="Foo1,Foo2" dataSet="&quot;b&quot;"/>',
            '  </testCaseClass>',
            '  <testCaseClass name="Sweetchuck\Robo\PHPUnit\TestsFixtures\Project01\Kernel\FooTest">',
            '    <testCaseMethod name="testDummy" groups="Foo1,Foo3"/>',
            '   </testCaseClass>',
            '</tests>',
        ];

        return [
            'basic' => [
                [
                    'exitCode' => 0,
                    'assets' => [
                        'phpunit.tests.xml' => implode("\n", $xml01),
                    ],
                ],
                [
                    'exitCode' => 0,
                    'stdOutput' => implode(
                        PHP_EOL,
                        array_merge(
                            [
                                'PHPUnit 9.5.10 by Sebastian Bergmann and contributors.',
                                '',
                            ],
                            $xml01,
                            [
                                'Wrote list of tests that would have been run to php://stdout',
                            ],
                        ),
                    ),
                    'stdError' => '',
                ],
            ],
        ];
    }
}
