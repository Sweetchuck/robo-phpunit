<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Unit;

use Codeception\Test\Unit;
use Sweetchuck\Robo\PHPUnit\PHPUnitConfigurationXmlParser;

/**
 * @covers \Sweetchuck\Robo\PHPUnit\PHPUnitConfigurationXmlParser
 */
class PHPUnitConfigurationXmlParserTest extends Unit
{

    /**
     * @return array<string, mixed>
     */
    public function casesParse(): array
    {
        return [
            'phpunit-7' => [
                [
                    'logging' => [
                        'coverage-text' => 'php://stdout',
                        'coverage-html' => 'reports/human/coverage/html',
                        'coverage-clover' => 'reports/machine/coverage/coverage.xml',
                    ],
                    'logging.directories' => [
                        'coverage-html' => 'reports/human/coverage/html',
                        'coverage-clover' => 'reports/machine/coverage',
                    ],
                ],
                implode(PHP_EOL, [
                    '<phpunit>',
                    '    <logging>',
                    '        <log',
                    '            type="coverage-text"',
                    '            target="php://stdout"/>',
                    '',
                    '        <log',
                    '            type="coverage-html"',
                    '            target="reports/human/coverage/html"/>',
                    '',
                    '        <log',
                    '            type="coverage-clover"',
                    '            target="reports/machine/coverage/coverage.xml"/>',
                    '    </logging>',
                    '</phpunit>',
                ]),
                '.',
            ],
            'phpunit-8' => [
                [
                    'logging' => [
                        'junit' => 'php://stdout',
                        'testdoxHtml' => 'reports/human/result/phpunit.html',
                        'coverage-clover' => 'reports/machine/coverage/phpunit.clover.xml',
                        'coverage-xml' => 'reports/machine/coverage/phpunit.xml',
                    ],
                    'logging.directories' => [
                        'testdoxHtml' => 'reports/human/result',
                        'coverage-clover' => 'reports/machine/coverage',
                        'coverage-xml' => 'reports/machine/coverage',
                    ],
                ],
                implode(PHP_EOL, [
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
                ]),
                '.',
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     *
     * @dataProvider casesParse
     */
    public function testParse(array $expected, string $xmlString, string $baseDir = ''): void
    {
        $parser = new PHPUnitConfigurationXmlParser();
        $this->assertEquals($expected, $parser->parse($xmlString, $baseDir));
    }
}
