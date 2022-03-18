<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Acceptance\Task;

use Codeception\Example;
use Sweetchuck\Robo\PHPUnit\Test\AcceptanceTester;
use Sweetchuck\Robo\PHPUnit\Test\Helper\RoboFiles\PHPUnitRoboFile;
use Symfony\Component\Finder\Finder;

/**
 * @covers \Sweetchuck\Robo\PHPUnit\Task\ListTestsTask<extended>
 * @covers \Sweetchuck\Robo\PHPUnit\PHPUnitTaskLoader
 */
class ListTestsTaskCest
{

    /**
     * @return array<string, dev-list-suites-examples-array>
     */
    protected function listSuitesExamples(): array
    {
        $fixturesDir = codecept_data_dir('fixtures');

        $projectDirs = (new Finder())
            ->in($fixturesDir)
            ->directories()
            ->name('project-*');

        $examples = [];
        /** @var \Symfony\Component\Finder\SplFileInfo $projectDir */
        foreach ($projectDirs as $projectDir) {
            $id = 'listTests:' . $projectDir->getRelativePathname();

            $examples[$id] = [
                'id' => $id,
                'expected' => [
                    'exitCode' => 0,
                    'stdOutput' => implode("\n", [
                        'phpunit.testMethods:',
                        "    - 'Sweetchuck\Robo\PHPUnit\Test\Fixtures\Project01\Unit\FooTest::testDummy'",
                        "    - 'Sweetchuck\Robo\PHPUnit\Test\Fixtures\Project01\Unit\FooTest::testDummy02\"a\"'",
                        "    - 'Sweetchuck\Robo\PHPUnit\Test\Fixtures\Project01\Unit\FooTest::testDummy02\"b\"'",
                        "    - 'Sweetchuck\Robo\PHPUnit\Test\Fixtures\Project01\Kernel\FooTest::testDummy'",
                        '',
                    ]),
                ],
                'args' => [
                    '--workingDirectory=' . $projectDir->getPathname(),
                ],
            ];
        }

        return $examples;
    }

    /**
     * @param Example<dev-list-suites-examples-array> $example
     *
     * @dataProvider listSuitesExamples
     */
    public function listSuites(AcceptanceTester $tester, Example $example): void
    {
        $tester->runRoboTask(
            $example['id'],
            PHPUnitRoboFile::class,
            'phpunit:list:tests',
            ...$example['args'],
        );
        $exitCode = $tester->getRoboTaskExitCode($example['id']);
        $stdOutput = $tester->getRoboTaskStdOutput($example['id']);
        $stdError = $tester->getRoboTaskStdError($example['id']);

        if (isset($example['expected']['exitCode'])) {
            $tester->assertSame($example['expected']['exitCode'], $exitCode, 'exitCode');
        }

        if (isset($example['expected']['stdOutput'])) {
            $tester->assertStringContainsString($example['expected']['stdOutput'], $stdOutput, 'stdOutput');
        }

        if (isset($example['expected']['stdError'])) {
            $tester->assertSame($example['expected']['stdError'], $stdError, 'stdError');
        }
    }
}
