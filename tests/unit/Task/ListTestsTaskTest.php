<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Unit\Task;

use PHPUnit\Framework\Attributes\CoversClass;
use Sweetchuck\Robo\PHPUnit\Task\BaseCliTask;
use Sweetchuck\Robo\PHPUnit\Task\BaseTask;
use Sweetchuck\Robo\PHPUnit\Task\ListTestsTask;

#[CoversClass(ListTestsTask::class)]
#[CoversClass(BaseCliTask::class)]
#[CoversClass(BaseTask::class)]
class ListTestsTaskTest extends BaseCliTaskTestBase
{

    protected function createTaskInstance(): ListTestsTask
    {
        return new ListTestsTask();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCommand(): array
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
    public static function casesRunSuccess(): array
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
