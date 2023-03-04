<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Unit\Task;

use Sweetchuck\Robo\PHPUnit\Task\ListTestsTask;

/**
 * @covers \Sweetchuck\Robo\PHPUnit\Task\ListTestsTask
 * @covers \Sweetchuck\Robo\PHPUnit\Task\BaseCliTask
 * @covers \Sweetchuck\Robo\PHPUnit\Task\BaseTask
 */
class ListTestsTaskTest extends BaseCliTaskTestBase
{

    protected function createTaskInstance(): ListTestsTask
    {
        return new ListTestsTask();
    }

    /**
     * {@inheritdoc}
     */
    public function casesGetCommand(): array
    {
        return [
            'basic' => [
                "php 'vendor/bin/phpunit' --list-tests",
                [],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function casesRunSuccess(): array
    {
        return [
            'basic' => [
                [
                    'exitCode' => 0,
                    'assets' => [
                        'phpunit.testMethods' => [
                            'Class01::method01',
                            'Class01::method02',
                        ],
                    ],
                ],
                [
                    'exitCode' => 0,
                    'stdOutput' => implode(PHP_EOL, [
                        'Foo',
                        ' - Class01::method01',
                        ' - Class01::method02',
                    ]),
                    'stdError' => '',
                ],
            ],
            'assetNamePrefix' => [
                [
                    'exitCode' => 0,
                    'assets' => [
                        'xy.phpunit.testMethods' => [
                            'Class01::method01',
                            'Class01::method02',
                        ],
                    ],
                ],
                [
                    'exitCode' => 0,
                    'stdOutput' => implode(PHP_EOL, [
                        'Foo',
                        ' - Class01::method01',
                        ' - Class01::method02',
                    ]),
                    'stdError' => '',
                ],
                [
                    'assetNamePrefix' => 'xy.',
                ],
            ],
        ];
    }
}
