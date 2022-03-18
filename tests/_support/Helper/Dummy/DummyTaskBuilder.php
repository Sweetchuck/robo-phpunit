<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Helper\Dummy;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Robo\Collection\CollectionBuilder;
use Robo\Common\TaskIO;
use Robo\Contract\BuilderAwareInterface;
use Robo\State\StateAwareTrait;
use Robo\TaskAccessor;
use Robo\Tasks as RoboTasks;
use Sweetchuck\Robo\PHPUnit\PHPUnitTaskLoader;

class DummyTaskBuilder implements BuilderAwareInterface, ContainerAwareInterface
{
    use TaskAccessor;
    use ContainerAwareTrait;
    use StateAwareTrait;
    use TaskIO;

    use PHPUnitTaskLoader {
        taskPHPUnitCoverageReportHtml as public;
        taskPHPUnitCoverageReportXml as public;
        taskPHPUnitListGroups as public;
        taskPHPUnitListSuites as public;
        taskPHPUnitListTests as public;
        taskPHPUnitListTestsXml as public;
        taskPHPUnitMergeCoveragePhp as public;
        taskPHPUnitParseConfigurationXml as public;
        taskPHPUnitRun as public;
        taskPHPUnitTestCasesToCsv as public;
        taskPHPUnitTestCasesToFileNames as public;
    }

    public function collectionBuilder(): CollectionBuilder
    {
        $commandFile = new RoboTasks();

        return CollectionBuilder::create($this->getContainer(), $commandFile);
    }
}
