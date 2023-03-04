<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Unit\Task;

use Sweetchuck\Robo\PHPUnit\Task\ListSuitesTask;

/**
 * @covers \Sweetchuck\Robo\PHPUnit\Task\ListSuitesTask
 * @covers \Sweetchuck\Robo\PHPUnit\Task\BaseCliTask
 * @covers \Sweetchuck\Robo\PHPUnit\Task\BaseTask
 */
class ListSuitesTaskTest extends BaseCliTaskTestBase
{

    protected function createTaskInstance(): ListSuitesTask
    {
        return new ListSuitesTask();
    }

    /**
     * {@inheritdoc}
     */
    public function casesGetCommand(): array
    {
        return [
            'basic' => [
                "php 'vendor/bin/phpunit' --list-suites",
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
                        'phpunit.suitNames' => [
                            'Suit01',
                            'Suit02',
                        ],
                    ],
                ],
                [
                    'exitCode' => 0,
                    'stdOutput' => implode(PHP_EOL, [
                        'Foo',
                        ' - Suit01',
                        ' - Suit02',
                    ]),
                    'stdError' => '',
                ],
            ],
            'assetNamePrefix' => [
                [
                    'exitCode' => 0,
                    'assets' => [
                        'xy.phpunit.suitNames' => [
                            'Suit01',
                            'Suit02',
                        ],
                    ],
                ],
                [
                    'exitCode' => 0,
                    'stdOutput' => implode(PHP_EOL, [
                        'Foo',
                        ' - Suit01',
                        ' - Suit02',
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
