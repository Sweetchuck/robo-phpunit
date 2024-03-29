<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Unit\Task;

use Codeception\Attribute\DataProvider;
use Sweetchuck\Robo\PHPUnit\Task\ParseConfigurationXmlTask;

/**
 * @covers \Sweetchuck\Robo\PHPUnit\Task\ParseConfigurationXmlTask
 * @covers \Sweetchuck\Robo\PHPUnit\Task\BaseTask
 */
class ParseConfigurationXmlTaskTest extends TaskTestBase
{
    protected function createTaskInstance(): ParseConfigurationXmlTask
    {
        return new ParseConfigurationXmlTask();
    }

    /**
     * @return array<string, mixed>
     */
    public function casesRunSuccess(): array
    {
        $xmlContent = implode(PHP_EOL, [
            '<phpunit>',
            '    <logging>',
            '        <junit outputFile="php://stdout" />',
            '        <testdoxHtml outputFile="reports/human/result/phpunit.html" />',
            '    </logging>',
            '    <coverage>',
            '        <report>',
            '            <clover outputFile="reports/machine/coverage/phpunit.clover.xml" />',
            '            <xml outputDirectory="reports/machine/coverage/phpunit.xml" />',
            '        </report>',
            '    </coverage>',
            '</phpunit>',
        ]);

        $assets = [
            'phpunit.logging' => [
                'junit' => 'php://stdout',
                'testdoxHtml' => 'reports/human/result/phpunit.html',
                'coverage-clover' => 'reports/machine/coverage/phpunit.clover.xml',
                'coverage-xml' => 'reports/machine/coverage/phpunit.xml',
            ],
            'phpunit.logging.directories' => [
                'testdoxHtml' => 'reports/human/result',
                'coverage-clover' => 'reports/machine/coverage',
                'coverage-xml' => 'reports/machine/coverage',
            ],
        ];

        return [
            'basic' => [
                [
                    'assets' => $assets,
                ],
                [
                    'xmlFile' => $xmlContent,
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
