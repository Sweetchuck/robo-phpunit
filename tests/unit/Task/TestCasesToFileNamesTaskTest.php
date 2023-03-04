<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Unit\Task;

use Codeception\Attribute\DataProvider;
use Sweetchuck\Robo\PHPUnit\Task\TestCasesToFileNamesTask;

/**
 * @covers \Sweetchuck\Robo\PHPUnit\Task\TestCasesToFileNamesTask
 * @covers \Sweetchuck\Robo\PHPUnit\Task\BaseTask
 */
class TestCasesToFileNamesTaskTest extends TaskTestBase
{
    protected function createTaskInstance(): TestCasesToFileNamesTask
    {
        return new TestCasesToFileNamesTask();
    }

    /**
     * @return array<string, mixed>
     */
    public function casesRunSuccess(): array
    {
        $xmlContent = implode("\n", [
            '<?xml version="1.0"?>',
            '<tests>',
            '    <testCaseClass name="Sweetchuck\Robo\PHPUnit\Test\Fixtures\Project01\Unit\FooTest">',
            '        <testCaseMethod name="testDummy" groups="Foo1,Foo2"/>',
            '        <testCaseMethod name="testDummy02" groups="Foo1,Foo2" dataSet="&quot;a&quot;"/>',
            '        <testCaseMethod name="testDummy02" groups="Foo1,Foo2" dataSet="&quot;b&quot;"/>',
            '    </testCaseClass>',
            '    <testCaseClass name="Sweetchuck\Robo\PHPUnit\Test\Fixtures\Project01\Kernel\FooTest">',
            '        <testCaseMethod name="testDummy" groups="Foo1,Foo3"/>',
            '    </testCaseClass>',
            '</tests>',
        ]);

        $fileNames = [
            'tests/_data/fixtures/project-01/Unit/FooTest.php',
            'tests/_data/fixtures/project-01/Kernel/FooTest.php',
        ];

        return [
            'basic' => [
                [
                    'assets' => [
                        'phpunit.tests.fileNames' => $fileNames,
                    ],
                ],
                [
                    'xmlFile' => $xmlContent,
                    'fileNameRelativeTo' => $this->selfProjectRoot(),
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<string, mixed> $options
     */
    #[DataProvider('casesRunSuccess')]
    public function testRunSuccess(array $expected, array $options): void
    {
        $task = $this->createTask();
        $task->setOptions($options);
        $result = $task->run();

        if (array_key_exists('assets', $expected)) {
            $assets = $result->getData();
            foreach ($expected['assets'] as $assetName => $assetValue) {
                $this->tester->assertArrayHasKey($assetName, $assets);
                $this->tester->assertSame($assetValue, $assets[$assetName], "asset.$assetName");
            }
        }
    }
}
