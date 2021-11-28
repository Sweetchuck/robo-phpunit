<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Unit\OutputParser;

use Codeception\Test\Unit;
use Sweetchuck\Robo\PHPUnit\OutputParser\ListTestsXmlOutputParser;

/**
 * @covers \Sweetchuck\Robo\PHPUnit\OutputParser\ListTestsXmlOutputParser
 */
class ListTestsXmlOutputParserTest extends Unit
{

    public function casesParse(): array
    {
        return [
            'error' => [
                [],
                1,
                '',
                '',
            ],
            'empty' => [
                [
                    'exitCode' => 2,
                    'assets' => [],
                ],
                0,
                '',
                '',
            ],
            'basic' => [
                [
                    'assets' => [
                        'xml' => implode(PHP_EOL, [
                            '<?xml version="1.0"?>',
                            '<tests>',
                            '</tests>',
                        ]),
                    ],
                ],
                0,
                implode(PHP_EOL, [
                    'foo',
                    '',
                    '<?xml version="1.0"?>',
                    '<tests>',
                    '</tests>',
                    'bar',
                ]),
                '',
            ],
        ];
    }

    /**
     * @dataProvider casesParse
     */
    public function testParse(array $expected, int $exitCode, string $stdOutput, string $stdError): void
    {
        $parser = new ListTestsXmlOutputParser();
        static::assertSame($expected, $parser->parse($exitCode, $stdOutput, $stdError));
    }
}
