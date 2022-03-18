<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Unit;

use Sweetchuck\Robo\PHPUnit\TestCasesConverter;
use Codeception\Test\Unit;
use Sweetchuck\Robo\PHPUnit\Test\UnitTester;

/**
 * @covers \Sweetchuck\Robo\PHPUnit\TestCasesConverter
 */
class TestCasesConverterTest extends Unit
{

    protected UnitTester $tester;

    protected function selfProjectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    /**
     * @return array<string, dev-to-file-names-success-case-array>
     */
    public function casesToFileNamesSuccess(): array
    {
        $selfProjectRoot = $this->selfProjectRoot();

        return [
            'real' => [
                [
                    'tests/_data/fixtures/project-01/Unit/FooTest.php',
                    'tests/_data/fixtures/project-01/Kernel/FooTest.php',
                ],
                implode("\n", [
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
                ]),
                $selfProjectRoot,
            ],
        ];
    }

    /**
     * @dataProvider casesToFileNamesSuccess
     *
     * @param array<string> $expected
     */
    public function testToFileNamesSuccess(array $expected, string $xmlString, string $fileNameRelativeTo): void
    {
        $converter = new TestCasesConverter();

        $this->tester->assertSame($expected, $converter->toFileNames($xmlString, $fileNameRelativeTo));
    }

    /**
     * @return array<string, dev-to-csv-test-case-array>
     */
    public function casesToCsv(): array
    {
        $xmlStringReal = implode("\n", [
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

        return [
            'empty' => [
                '',
                [
                    $this->getEmptyXml(),
                    $this->selfProjectRoot(),
                ],
            ],
            'real' => [
                implode("\n", [
                    // phpcs:disable Generic.Files.LineLength.TooLong
                    'file,class,classRegexSafe,method,dataSet,dataSetRegexSafe,groups',
                    'tests/_data/fixtures/project-01/Unit/FooTest.php,"Sweetchuck\Robo\PHPUnit\Test\Fixtures\Project01\Unit\FooTest","Sweetchuck\\\\Robo\\\\PHPUnit\\\\Test\\\\Fixtures\\\\Project01\\\\Unit\\\\FooTest",testDummy,,,"Foo1,Foo2"',
                    'tests/_data/fixtures/project-01/Unit/FooTest.php,"Sweetchuck\Robo\PHPUnit\Test\Fixtures\Project01\Unit\FooTest","Sweetchuck\\\\Robo\\\\PHPUnit\\\\Test\\\\Fixtures\\\\Project01\\\\Unit\\\\FooTest",testDummy02,"""a""","""a""","Foo1,Foo2"',
                    'tests/_data/fixtures/project-01/Unit/FooTest.php,"Sweetchuck\Robo\PHPUnit\Test\Fixtures\Project01\Unit\FooTest","Sweetchuck\\\\Robo\\\\PHPUnit\\\\Test\\\\Fixtures\\\\Project01\\\\Unit\\\\FooTest",testDummy02,"""b""","""b""","Foo1,Foo2"',
                    'tests/_data/fixtures/project-01/Kernel/FooTest.php,"Sweetchuck\Robo\PHPUnit\Test\Fixtures\Project01\Kernel\FooTest","Sweetchuck\\\\Robo\\\\PHPUnit\\\\Test\\\\Fixtures\\\\Project01\\\\Kernel\\\\FooTest",testDummy,,,"Foo1,Foo3"',
                    '',
                    // phpcs:enable Generic.Files.LineLength.TooLong
                ]),
                [
                    $xmlStringReal,
                    $this->selfProjectRoot(),
                ],
            ],
            'real, granularity:file' => [
                implode("\n", [
                    // phpcs:disable Generic.Files.LineLength.TooLong
                    'file,class,classRegexSafe,method,dataSet,dataSetRegexSafe,groups',
                    'tests/_data/fixtures/project-01/Unit/FooTest.php,"Sweetchuck\Robo\PHPUnit\Test\Fixtures\Project01\Unit\FooTest","Sweetchuck\\\\Robo\\\\PHPUnit\\\\Test\\\\Fixtures\\\\Project01\\\\Unit\\\\FooTest",testDummy02,"""b""","""b""","Foo1,Foo2"',
                    'tests/_data/fixtures/project-01/Kernel/FooTest.php,"Sweetchuck\Robo\PHPUnit\Test\Fixtures\Project01\Kernel\FooTest","Sweetchuck\\\\Robo\\\\PHPUnit\\\\Test\\\\Fixtures\\\\Project01\\\\Kernel\\\\FooTest",testDummy,,,"Foo1,Foo3"',
                    '',
                    // phpcs:enable Generic.Files.LineLength.TooLong
                ]),
                [
                    $xmlStringReal,
                    $this->selfProjectRoot(),
                    [],
                    ['file' => true],
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesToCsv
     *
     * @param dev-to-csv-args-array $args
     */
    public function testToCsv(string $excepted, array $args): void
    {
        $converter = new TestCasesConverter();
        $this->tester->assertSame($excepted, $converter->toCsv(...$args));
    }

    protected function getEmptyXml(): string
    {
        return "<?xml version=\"1.0\"?>\n<tests>\n</tests>\n";
    }
}
