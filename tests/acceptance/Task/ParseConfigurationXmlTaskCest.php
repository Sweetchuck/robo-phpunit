<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Acceptance\Task;

use Codeception\Example;
use Sweetchuck\Robo\PHPUnit\Test\AcceptanceTester;
use Sweetchuck\Robo\PHPUnit\Test\Helper\RoboFiles\PHPUnitRoboFile;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * @covers \Sweetchuck\Robo\PHPUnit\Task\ParseConfigurationXmlTask<extended>
 * @covers \Sweetchuck\Robo\PHPUnit\PHPUnitTaskLoader
 */
class ParseConfigurationXmlTaskCest
{

    /**
     * @return array<string, dev-parse-xml-examples-array>
     */
    protected function parseXmlExamples(): array
    {
        $fixturesDir = codecept_data_dir('fixtures');

        $phpUnitXmlFiles = (new Finder())
            ->in("$fixturesDir/parseConfigurationXml")
            ->name('phpunit.*.xml');

        $replacements = [
            '%workingDirectory%' => $fixturesDir,
        ];
        $examples = [];
        /** @var \Symfony\Component\Finder\SplFileInfo $phpUnitXmlFile */
        foreach ($phpUnitXmlFiles as $phpUnitXmlFile) {
            $id = $phpUnitXmlFile->getRelativePathname();

            $expectedFileName = preg_replace(
                '/\.xml$/',
                '.yml',
                $phpUnitXmlFile->getPathname()
            );
            $examples[$id] = [
                'id' => $id,
                'expected' => Yaml::parse(strtr(
                    (string) file_get_contents($expectedFileName),
                    $replacements,
                )),
                'args' => [
                    $phpUnitXmlFile->getPathname(),
                ],
            ];
        }

        return $examples;
    }

    /**
     * @param Example<dev-parse-xml-examples-array> $example
     *
     * @dataProvider parseXmlExamples
     */
    public function parseXml(AcceptanceTester $tester, Example $example): void
    {
        $tester->runRoboTask(
            $example['id'],
            PHPUnitRoboFile::class,
            'phpunit:parse:xml',
            ...$example['args'],
        );
        $exitCode = $tester->getRoboTaskExitCode($example['id']);
        $stdOutput = $tester->getRoboTaskStdOutput($example['id']);
        $stdError = $tester->getRoboTaskStdError($example['id']);

        $tester->assertSame($example['expected']['exitCode'], $exitCode);
        $tester->assertSame($example['expected']['stdOutput'], $stdOutput);
        $tester->assertSame($example['expected']['stdError'], $stdError);
    }
}
