<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Unit\Task;

use PHPUnit\Framework\Attributes\CoversClass;
use Sweetchuck\Robo\PHPUnit\Task\BaseCliTask;
use Sweetchuck\Robo\PHPUnit\Task\BaseTask;
use Sweetchuck\Robo\PHPUnit\Task\ListGroupsTask;

#[CoversClass(ListGroupsTask::class)]
#[CoversClass(BaseCliTask::class)]
#[CoversClass(BaseTask::class)]
class ListGroupsTaskTest extends BaseCliTaskTestBase
{

    protected function createTaskInstance(): ListGroupsTask
    {
        return new ListGroupsTask();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCommand(): array
    {
        return [
            'basic' => [
                "php 'vendor/bin/phpunit' --list-groups",
                [],
            ],
            'arguments vector' => [
                "php 'vendor/bin/phpunit' --list-groups 'my-dir'",
                [
                    'arguments' => ['my-dir'],
                ],
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
                        'phpunit.groupNames' => [
                            'Group01',
                            'Group02',
                        ],
                    ],
                ],
                [
                    'exitCode' => 0,
                    'stdOutput' => implode(PHP_EOL, [
                        'Foo',
                        ' - Group01',
                        ' - Group02',
                    ]),
                    'stdError' => '',
                ],
            ],
            'assetNamePrefix' => [
                [
                    'exitCode' => 0,
                    'assets' => [
                        'xy.phpunit.groupNames' => [
                            'Group01',
                            'Group02',
                        ],
                    ],
                ],
                [
                    'exitCode' => 0,
                    'stdOutput' => implode(PHP_EOL, [
                        'Foo',
                        ' - Group01',
                        ' - Group02',
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
