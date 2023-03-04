<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Unit\Task;

use Codeception\Test\Unit;
use League\Container\Container as LeagueContainer;
use Psr\Container\ContainerInterface;
use Robo\Application;
use Robo\Collection\CollectionBuilder;
use Robo\Config\Config;
use Robo\Config\Config as RoboConfig;
use Robo\Robo;
use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyProcess;
use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyProcessHelper;
use Sweetchuck\Robo\PHPUnit\Task\BaseTask;
use Sweetchuck\Robo\PHPUnit\Test\Helper\Dummy\DummyTaskBuilder;
use Sweetchuck\Robo\PHPUnit\Test\UnitTester;
use Symfony\Component\Console\Application as SymfonyApplication;
use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyOutput;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\ErrorHandler\BufferingLogger;

abstract class TaskTestBase extends Unit
{
    protected ContainerInterface $container;

    protected RoboConfig $config;

    protected CollectionBuilder $builder;

    protected UnitTester $tester;

    protected DummyTaskBuilder $taskBuilder;

    protected function selfProjectRoot(): string
    {
        return dirname(__DIR__, 3);
    }

    /**
     * @retrun void
     * @phpstan-return void
     */
    public function _before()
    {
        parent::_before();

        DummyProcess::reset();

        Robo::unsetContainer();
        $this->container = new LeagueContainer();
        $application = new SymfonyApplication('Sweetchuck - Robo PHPUnit', '3.0.0');
        $application->getHelperSet()->set(new DummyProcessHelper(), 'process');
        $this->config = new Config();
        $input = null;
        $output = new DummyOutput([
            'verbosity' => OutputInterface::VERBOSITY_DEBUG,
        ]);

        $this->container->add('container', $this->container);

        Robo::configureContainer($this->container, $application, $this->config, $input, $output);
        $this->container->add('logger', BufferingLogger::class);
    }

    protected function createTask(): BaseTask
    {
        $container = new LeagueContainer();
        $application = new Application('Sweetchuck - Robo PHPLint', '1.0.0');
        $application->getHelperSet()->set(new DummyProcessHelper(), 'process');
        $config = new Config();
        $output = new DummyOutput([]);
        $loggerOutput = new DummyOutput([]);
        $logger = new ConsoleLogger($loggerOutput);

        $container->add('output', $output);
        $container->add('logger', $logger);
        $container->add('config', $config);
        $container->add('application', $application);

        $task = $this->createTaskInstance();
        $task->setContainer($container);
        $task->setOutput($output);
        $task->setLogger($logger);

        return $task;
    }

    abstract protected function createTaskInstance(): BaseTask;
}
