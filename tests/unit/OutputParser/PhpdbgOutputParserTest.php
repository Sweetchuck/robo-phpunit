<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Unit\OutputParser;

use Codeception\Test\Unit;
use Sweetchuck\Robo\PHPUnit\OutputParser\PhpdbgOutputParser;

/**
 * @covers \Sweetchuck\Robo\PHPUnit\OutputParser\ListOutputParser
 */
class PhpdbgOutputParserTest extends Unit
{

    /**
     * @return array<string, mixed>
     */
    public function casesParse(): array
    {
        $color = "\x1b\[1;31m";
        $errorOpenPlain = '[Could not open file foo.php';
        $errorOpenColor = $color . $errorOpenPlain;
        $errorCompilePlain = '[Failed to compile foo.php';
        $errorCompileColor = $color . $errorCompilePlain;

        return [
            'empty' => [
                [
                    'exitCode' => 0,
                    'errorMessages' => [],
                ],
                0,
                '',
                '',
            ],
            'Could not open file - plain' => [
                [
                    'exitCode' => 121,
                    'errorMessages' => [
                        $errorOpenPlain,
                    ],
                ],
                0,
                implode(PHP_EOL, [
                    $errorOpenPlain,
                    'abc',
                    '',
                ]),
                '',
            ],
            'Could not open file - color' => [
                [
                    'exitCode' => 121,
                    'errorMessages' => [
                        $errorOpenColor,
                        $errorCompileColor,
                    ],
                ],
                0,
                implode(PHP_EOL, [
                    $errorOpenColor,
                    $errorCompileColor,
                    'abc',
                    '',
                ]),
                '',
            ],
            'Failed to compile - plain' => [
                [
                    'exitCode' => 122,
                    'errorMessages' => [
                        $errorCompilePlain,
                    ],
                ],
                0,
                implode(PHP_EOL, [
                    $errorCompilePlain,
                    'abc',
                    '',
                ]),
                '',
            ],
            'Failed to compile - color' => [
                [
                    'exitCode' => 122,
                    'errorMessages' => [
                        $errorCompileColor,
                        $errorOpenColor,
                    ],
                ],
                0,
                implode(PHP_EOL, [
                    $errorCompileColor,
                    $errorOpenColor,
                    'abc',
                    '',
                ]),
                '',
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     *
     * @dataProvider casesParse
     */
    public function testParse(array $expected, int $exitCode, string $stdOutput, string $stdError): void
    {
        $parser = new PhpdbgOutputParser();
        static::assertSame($expected, $parser->parse($exitCode, $stdOutput, $stdError));
    }
}
