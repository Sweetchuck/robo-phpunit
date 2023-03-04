<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Unit\OutputParser;

use Codeception\Attribute\DataProvider;
use Sweetchuck\Robo\PHPUnit\OutputParser\ListOutputParser;

/**
 * @covers \Sweetchuck\Robo\PHPUnit\OutputParser\ListOutputParser
 */
class ListOutputParserTest extends \Codeception\Test\Unit
{

    /**
     * @return array<string, mixed>
     */
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
                    'assets' => [
                        'items' => [],
                    ],
                ],
                0,
                '',
                '',
            ],
            'basic' => [
                [
                    'assets' => [
                        'items' => [
                            'a b',
                            'cd',
                        ],
                    ],
                ],
                0,
                implode(PHP_EOL, [
                    'header 01',
                    ' - a b',
                    ' - cd',
                    '',
                ]),
                '',
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     */
    #[DataProvider('casesParse')]
    public function testParse(array $expected, int $exitCode, string $stdOutput, string $stdError): void
    {
        $parser = new ListOutputParser();
        static::assertSame($expected, $parser->parse($exitCode, $stdOutput, $stdError));
    }
}
