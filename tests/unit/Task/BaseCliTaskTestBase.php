<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Unit\Task;

use Codeception\Attribute\DataProvider;
use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyProcess;
use Sweetchuck\Robo\PHPUnit\Task\BaseCliTask;

/**
 * @method BaseCliTask createTask()
 */
abstract class BaseCliTaskTestBase extends TaskTestBase
{

    abstract protected function createTaskInstance(): BaseCliTask;

    /**
     * @return array<string, mixed>
     */
    abstract public function casesGetCommand(): array;

    /**
     * @param array<string, mixed> $options
     */
    #[DataProvider('casesGetCommand')]
    public function testGetCommand(string $expected, array $options): void
    {
        $task = $this->createTask();
        $task->setOptions($options);

        $this->tester->assertSame($expected, $task->getCommand());
    }

    /**
     * @return array<string, mixed>
     */
    abstract public function casesRunSuccess(): array;

    /**
     * @param array<string, mixed> $expected
     * @param dev-process-result-array $processProphecy
     * @param array<string, mixed> $options
     */
    #[DataProvider('casesRunSuccess')]
    public function testRunSuccess(array $expected, array $processProphecy, array $options = []): void
    {
        DummyProcess::$prophecy[] = $processProphecy;

        $task = $this->createTask();
        $result = $task
            ->setOptions($options)
            ->run();

        if (array_key_exists('exitCode', $expected)) {
            $this->tester->assertSame($expected['exitCode'], $result->getExitCode());
        }

        if (array_key_exists('message', $expected)) {
            $this->tester->assertSame($expected['message'], $result->getMessage());
        }

        if (array_key_exists('assets', $expected)) {
            $assets = $result->getData();
            foreach ($expected['assets'] as $assetName => $assetValue) {
                $this->tester->assertArrayHasKey($assetName, $assets);
                $this->tester->assertSame($assetValue, $assets[$assetName]);
            }
        }
    }
}
