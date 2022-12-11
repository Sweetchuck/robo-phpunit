<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Helper\RoboFiles;

use Robo\Collection\CollectionBuilder;
use Robo\Contract\TaskInterface;
use Robo\Tasks;
use Sweetchuck\Robo\PHPUnit\PHPUnitTaskLoader;
use Robo\State\Data as RoboStateData;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Filesystem\Path;

class PHPUnitRoboFile extends Tasks
{
    use PHPUnitTaskLoader;

    protected string $composerBinDir = 'vendor/bin';

    /**
     * {@inheritdoc}
     */
    protected function output()
    {
        return $this->getContainer()->get('output');
    }

    /**
     * @phpstan-param array<string, mixed> $options
     *
     * @command phpunit:list:groups
     */
    public function listGroups(
        array $options = [
            'workingDirectory' => './tests/_data/fixtures/project-01',
            'configuration' => 'phpunit.xml.dist',
        ]
    ): TaskInterface {
        $options['phpunitExecutable'] = $this->getPhpunitExecutable($options['workingDirectory']);

        return $this
            ->collectionBuilder()
            ->addTask($this->taskPHPUnitListGroups($options))
            ->addCode($this->getTaskDumpAssets());
    }

    /**
     * @phpstan-param array<string, mixed> $options
     *
     * @command phpunit:list:suites
     */
    public function listSuites(
        array $options = [
            'workingDirectory' => './tests/_data/fixtures/project-01',
            'configuration' => 'phpunit.xml.dist',
        ]
    ): TaskInterface {
        $options['phpunitExecutable'] = $this->getPhpunitExecutable($options['workingDirectory']);

        return $this
            ->collectionBuilder()
            ->addTask($this->taskPHPUnitListSuites($options))
            ->addCode($this->getTaskDumpAssets());
    }

    /**
     * @phpstan-param array<string, mixed> $options
     *
     * @command phpunit:list:tests
     */
    public function listTests(
        array $options = [
            'workingDirectory' => 'tests/_data/fixtures/project-01',
            'configuration' => 'phpunit.xml.dist',
        ]
    ): TaskInterface {
        $options['phpunitExecutable'] = $this->getPhpunitExecutable($options['workingDirectory']);

        return $this
            ->collectionBuilder()
            ->addTask($this->taskPHPUnitListTests($options))
            ->addCode($this->getTaskDumpAssets());
    }

    /**
     * @command phpunit:parse:xml
     */
    public function parseXml(string $xmlFile = 'tests/_data/fixtures/parseXml/phpunit.basic.xml'): CollectionBuilder
    {
        $options['phpunitExecutable'] = $this->getPhpunitExecutable();
        $options['xmlFile'] = $xmlFile;

        return $this
            ->collectionBuilder()
            ->addTask($this->taskPHPUnitParseConfigurationXml($options))
            ->addCode($this->getTaskDumpAssets());
    }

    /**
     * @phpstan-param array<string, mixed> $options
     *
     * @command phpunit:run
     */
    public function run(
        array $options = [
            'workingDirectory' => 'tests/_data/fixtures/project-01',
            'configuration' => 'phpunit.xml.dist',
        ]
    ): CollectionBuilder {
        $options['phpunitExecutable'] = $this->getPhpunitExecutable($options['workingDirectory']);

        return $this->taskPHPUnitRun($options);
    }

    protected function getTaskDumpAssets(): \Closure
    {
        return function (RoboStateData $data): int {
            $assets = $data->getData();
            unset($assets['time']);
            $this->output()->write(Yaml::dump($assets, 99));

            return 0;
        };
    }

    protected function getRoboPhpUnitRootDir(): string
    {
        return Path::join(__DIR__, '/../../../..');
    }

    protected function getPhpunitExecutable(string $workingDirectory = '.'): string
    {
        $rootDir = $this->getRoboPhpUnitRootDir();
        $workingDirectory = preg_replace('@^\.(/|$)@', getcwd() . '/', $workingDirectory);

        return Path::makeRelative("$rootDir/{$this->composerBinDir}/phpunit", $workingDirectory);
    }
}
