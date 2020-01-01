<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Tests\Unit\Task;

class ListSuitesTaskTest extends BaseCliTaskTestBase
{

    /**
     * {@inheritdoc}
     */
    protected function initTask()
    {
        $this->task = $this->taskBuilder->taskPHPUnitListSuitesTask();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function casesGetCommand(): array
    {
        return [
            'basic' => [
                "phpdbg -qrr 'vendor/bin/phpunit' --list-suites",
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
