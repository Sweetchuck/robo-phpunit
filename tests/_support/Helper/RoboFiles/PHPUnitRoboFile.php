<?php

namespace Sweetchuck\Robo\PHPUnit\Test\Helper\RoboFiles;

use Robo\Collection\CollectionBuilder;
use Robo\Tasks;
use Sweetchuck\Robo\PHPUnit\PHPUnitTaskLoader;
use Robo\State\Data as RoboStateData;
use Symfony\Component\Yaml\Yaml;
use Webmozart\PathUtil\Path;

class PHPUnitRoboFile extends Tasks
{
    use PHPUnitTaskLoader;

    /**
     * @var string
     */
    protected $composerBinDir = 'bin';

    /**
     * {@inheritdoc}
     */
    protected function output()
    {
        return $this->getContainer()->get('output');
    }

    /**
     * @command phpunit:list:groups
     */
    public function listGroups(array $args)
    {
        $rootDir = $this->getRoboPhpUnitRootDir();

        return $this
            ->collectionBuilder()
            ->addTask(
                $this
                    ->taskPHPUnitListGroupsTask()
                    ->setPhpunitExecutable("$rootDir/{$this->composerBinDir}/phpunit")
                    ->setArguments($args)
            )
            ->addCode(function (RoboStateData $data) {
                $assets = $data->getData();
                unset($assets['time']);

                $this
                    ->output()
                    ->write(Yaml::dump($assets, 99));
            });
    }

    /**
     * @command phpunit:list:suites
     */
    public function listSuites(string $configuration = 'phpunit.xml.dist')
    {
        $rootDir = $this->getRoboPhpUnitRootDir();

        return $this
            ->collectionBuilder()
            ->addTask(
                $this
                    ->taskPHPUnitListSuitesTask()
                    ->setPhpunitExecutable("$rootDir/{$this->composerBinDir}/phpunit")
                    ->setConfiguration($configuration)
            )
            ->addCode(function (RoboStateData $data) {
                $assets = $data->getData();
                unset($assets['time']);

                $this
                    ->output()
                    ->write(Yaml::dump($assets, 99));
            });
    }

    /**
     * @command phpunit:list:tests
     */
    public function listTests(array $args)
    {
        $rootDir = $this->getRoboPhpUnitRootDir();

        return $this
            ->collectionBuilder()
            ->addTask(
                $this
                    ->taskPHPUnitListTestsTask()
                    ->setPhpunitExecutable("$rootDir/{$this->composerBinDir}/phpunit")
                    ->setArguments($args)
            )
            ->addCode(function (RoboStateData $data) {
                $assets = $data->getData();
                unset($assets['time']);

                $this
                    ->output()
                    ->write(Yaml::dump($assets, 99));
            });
    }

    /**
     * @command phpunit:parse:xml
     */
    public function parseXml(
        string $workingDirectory,
        string $xmlFile = ''
    ): CollectionBuilder {
        return $this
            ->collectionBuilder()
            ->addTask(
                $this
                    ->taskPHPUnitParseXml()
                    ->setWorkingDirectory($workingDirectory)
                    ->setXmlFile($xmlFile)
            )
            ->addCode(function (RoboStateData $data) {
                $assets = $data->getData();
                unset($assets['time']);

                $this
                    ->getContainer()
                    ->get('output')
                    ->writeln(Yaml::dump($assets, 99));

                return 0;
            });
    }

    /**
     * @command phpunit:run
     */
    public function run(
        string $workingDirectory,
        array $options = [
            'configuration' => '',
        ]
    ): CollectionBuilder {
        return $this
            ->taskPHPUnitRun()
            ->setWorkingDirectory($workingDirectory)
            ->setPhpunitExecutable($this->getPhpunitExecutable($workingDirectory))
            ->setConfiguration($options['configuration']);
    }

    protected function getRoboPhpUnitRootDir(): string
    {
        return Path::makeRelative(__DIR__ . '/../../../..', getcwd());
    }

    protected function getPhpunitExecutable(string $workingDirectory = ''): string
    {
        $rootDir = $this->getRoboPhpUnitRootDir();

        return Path::makeRelative("$rootDir/{$this->composerBinDir}/phpunit", $workingDirectory);
    }
}
