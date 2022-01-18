<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Acceptance\Task;

use Codeception\Example;
use Sweetchuck\Robo\PHPUnit\Test\AcceptanceTester;
use Sweetchuck\Robo\PHPUnit\Test\Helper\RoboFiles\PHPUnitRoboFile;
use Symfony\Component\Finder\Finder;

/**
 * @covers \Sweetchuck\Robo\PHPUnit\Task\ListGroupsTask<extended>
 * @covers \Sweetchuck\Robo\PHPUnit\PHPUnitTaskLoader
 */
class ListGroupsTaskCest
{
    protected function listGroupsExamples(): array
    {
        $fixturesDir = codecept_data_dir('fixtures');

        $projectDirs = (new Finder())
            ->in($fixturesDir)
            ->directories()
            ->name('project-*');

        $examples = [];
        /** @var \Symfony\Component\Finder\SplFileInfo $projectDir */
        foreach ($projectDirs as $projectDir) {
            $id = 'listGroups:' . $projectDir->getRelativePathname();

            $examples[$id] = [
                'id' => $id,
                'expected' => [
                    'exitCode' => 0,
                    'stdOutput' => implode("\n", [
                        'phpunit.groupNames:',
                        '    - Foo1',
                        '    - Foo2',
                        '    - Foo3',
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
     * @dataProvider listGroupsExamples
     */
    public function listGroups(AcceptanceTester $tester, Example $example)
    {
        $tester->runRoboTask(
            $example['id'],
            PHPUnitRoboFile::class,
            'phpunit:list:groups',
            ...$example['args'],
        );
        $exitCode = $tester->getRoboTaskExitCode($example['id']);
        $stdOutput = $tester->getRoboTaskStdOutput($example['id']);
        $stdError = $tester->getRoboTaskStdError($example['id']);

        if (isset($example['expected']['exitCode'])) {
            $tester->assertSame(
                $example['expected']['exitCode'],
                $exitCode,
                "exitCode;\n---\n$stdOutput\n---\n$stdError",
            );
        }

        if (isset($example['expected']['stdOutput'])) {
            $tester->assertStringContainsString($example['expected']['stdOutput'], $stdOutput, 'stdOutput');
        }

        if (isset($example['expected']['stdError'])) {
            $tester->assertSame($example['expected']['stdError'], $stdError, 'stdError');
        }
    }
}
