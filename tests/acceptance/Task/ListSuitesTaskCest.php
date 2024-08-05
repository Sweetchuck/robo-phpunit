<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Acceptance\Task;

use Codeception\Attribute\DataProvider;
use Codeception\Example;
use PHPUnit\Framework\Attributes\CoversClass;
use Sweetchuck\Robo\PHPUnit\PHPUnitTaskLoader;
use Sweetchuck\Robo\PHPUnit\Task\BaseCliTask;
use Sweetchuck\Robo\PHPUnit\Task\BaseTask;
use Sweetchuck\Robo\PHPUnit\Task\ListSuitesTask;
use Sweetchuck\Robo\PHPUnit\Test\AcceptanceTester;
use Sweetchuck\Robo\PHPUnit\Test\Helper\RoboFiles\PHPUnitRoboFile;
use Symfony\Component\Finder\Finder;

#[CoversClass(ListSuitesTask::class)]
#[CoversClass(BaseCliTask::class)]
#[CoversClass(BaseTask::class)]
#[CoversClass(PHPUnitTaskLoader::class)]
class ListSuitesTaskCest
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
            $id = 'listSuites:' . $projectDir->getRelativePathname();

            $examples[$id] = [
                'id' => $id,
                'expected' => [
                    'exitCode' => 0,
                    'stdOutput' => implode("\n", [
                        'phpunit.suitNames:',
                        '    - Unit',
                        '    - Kernel',
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
     * @param \Codeception\Example<dev-list-suites-examples-array> $example
     */
    #[DataProvider('listSuitesExamples')]
    public function listSuites(AcceptanceTester $tester, Example $example): void
    {
        $tester->runRoboTask(
            $example['id'],
            PHPUnitRoboFile::class,
            'phpunit:list:suites',
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
