<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Unit\Task;

use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyProcess;

abstract class BaseCliTaskTestBase extends TaskTestBase
{

    /**
     * @return array<string, mixed>
     */
    abstract public function casesGetCommand(): array;

    /**
     * @param array<string, mixed> $options
     *
     * @dataProvider casesGetCommand
     */
    public function testGetCommand(string $expected, array $options): void
    {
        $this->task->setOptions($options);

        $this->tester->assertEquals($expected, $this->task->getCommand());
    }

    /**
     * @return array<string, mixed>
     */
    abstract public function casesRunSuccess(): array;

    /**
     * @param array<string, mixed> $expected
     * @param dev-process-result-array $processProphecy
     * @param array<string, mixed> $options
     *
     * @dataProvider casesRunSuccess
     */
    public function testRunSuccess(array $expected, array $processProphecy, array $options = []): void
    {
        $instanceIndex = count(DummyProcess::$instances);
        DummyProcess::$prophecy[$instanceIndex] = $processProphecy;

        $result = $this->task
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
