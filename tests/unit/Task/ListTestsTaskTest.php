<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Unit\Task;

/**
 * @covers \Sweetchuck\Robo\PHPUnit\Task\ListTestsTask<extended>
 * @covers \Sweetchuck\Robo\PHPUnit\PHPUnitTaskLoader
 */
class ListTestsTaskTest extends BaseCliTaskTestBase
{

    /**
     * {@inheritdoc}
     */
    protected function initTask()
    {
        $this->task = $this->taskBuilder->taskPHPUnitListTestsTask();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function casesGetCommand(): array
    {
        return [
            'basic' => [
                "phpdbg -qrr 'vendor/bin/phpunit' --list-tests",
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
