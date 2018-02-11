<?php

namespace Sweetchuck\Robo\PHPUnit\Tests\Acceptance\Task;

use Codeception\Example;
use Sweetchuck\Robo\PHPUnit\Test\AcceptanceTester;
use Sweetchuck\Robo\PHPUnit\Test\Helper\RoboFiles\PHPUnitRoboFile;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;
use Webmozart\PathUtil\Path;

class ParseXmlTaskCest
{
    protected function parseXmlExamples(): array
    {
        $fixturesDir = codecept_data_dir('fixtures');

        $phpUnitXmlFiles = (new Finder())
            ->in($fixturesDir)
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
                'workingDirectory' => Path::join($fixturesDir, $phpUnitXmlFile->getRelativePath()),
                'xmlFile' => $phpUnitXmlFile->getFilename(),
                'expected' => Yaml::parse(strtr(file_get_contents($expectedFileName), $replacements)),
            ];
        }

        return $examples;
    }

    /**
     * @dataProvider parseXmlExamples
     */
    public function parseXml(AcceptanceTester $tester, Example $example)
    {
        $tester->runRoboTask(
            $example['id'],
            PHPUnitRoboFile::class,
            'phpunit:parse:xml',
            $example['workingDirectory'],
            $example['xmlFile']
        );
        $exitCode = $tester->getRoboTaskExitCode($example['id']);
        $stdOutput = $tester->getRoboTaskStdOutput($example['id']);
        $stdError = $tester->getRoboTaskStdError($example['id']);

        $tester->assertEquals($example['expected']['exitCode'], $exitCode);
        $tester->assertEquals($example['expected']['stdOutput'], $stdOutput);
        $tester->assertEquals($example['expected']['stdError'], $stdError);
    }
}
