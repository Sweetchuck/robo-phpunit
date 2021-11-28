<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Unit\Task;

/**
 * @covers \Sweetchuck\Robo\PHPUnit\Task\ParseConfigurationXmlTask<extended>
 * @covers \Sweetchuck\Robo\PHPUnit\PHPUnitTaskLoader
 */
class ParseConfigurationXmlTaskTest extends TaskTestBase
{
    protected function initTask()
    {
        $this->task = $this->taskBuilder->taskPHPUnitParseConfigurationXml();
    }

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
     * @dataProvider casesRunSuccess
     */
    public function testRunSuccess($expected, array $options): void
    {
        $this->task->setOptions($options);
        $result = $this->task->run();

        if (array_key_exists('assets', $expected)) {
            $assets = $result->getData();
            foreach ($expected['assets'] as $assetName => $assetValue) {
                $this->tester->assertArrayHasKey($assetName, $assets);
                $this->tester->assertSame($assetValue, $assets[$assetName], "asset.$assetName");
            }
        }
    }
}
