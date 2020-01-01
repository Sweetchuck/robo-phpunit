<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Tests\Unit;

use Codeception\Test\Unit;
use Sweetchuck\Robo\PHPUnit\PHPUnitXmlParser;

class PHPUnitXmlParserTest extends Unit
{
    public function casesParse(): array
    {
        return [
            'basic' => [
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
        ];
    }

    /**
     * @dataProvider casesParse
     */
    public function testParse(array $expected, string $xmlString, string $baseDir = '')
    {
        $parser = new PHPUnitXmlParser();
        $this->assertEquals($expected, $parser->parse($xmlString, $baseDir));
    }
}
