<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Tests\Unit\Task;

use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyProcess;

abstract class BaseCliTaskTestBase extends TaskTestBase
{
    abstract public function casesGetCommand(): array;

    /**
     * @dataProvider casesGetCommand
     */
    public function testGetCommand(string $expected, array $options)
    {
        $this->task->setOptions($options);

        $this->tester->assertEquals($expected, $this->task->getCommand());
    }

    abstract public function casesRunSuccess(): array;

    /**
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
                $this->tester->assertSame($assetValue, $assets[$assetName]);
            }
        }
    }
}
