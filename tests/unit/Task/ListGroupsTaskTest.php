<?php

namespace Sweetchuck\Robo\PHPUnit\Tests\Unit\Task;

use Sweetchuck\Robo\PHPUnit\Task\ListGroupsTask;

class ListGroupsTaskTest extends BaseCliTaskTestBase
{
    /**
     * {@inheritdoc}
     */
    protected function initTask()
    {
        $this->task = new ListGroupsTask();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function casesGetCommand(): array
    {
        return [
            'basic' => [
                'phpdbg -qrr vendor/bin/phpunit --list-groups',
                [],
            ],
            'arguments vector' => [
                "phpdbg -qrr vendor/bin/phpunit --list-groups 'my-dir'",
                [
                    'arguments' => ['my-dir'],
                ],
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
