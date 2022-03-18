<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit;

use League\Container\ContainerAwareInterface;
use Robo\Collection\CollectionBuilder;
use Consolidation\AnnotatedCommand\Output\OutputAwareInterface;

trait PHPUnitTaskLoader
{

    /**
     * @param task-phpunit-coverage-report-html-options $options
     *
     * @return \Sweetchuck\Robo\PHPUnit\Task\CoverageReportHtmlTask&\Robo\Collection\CollectionBuilder
     */
    protected function taskPHPUnitCoverageReportHtml(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\PHPUnit\Task\CoverageReportHtmlTask&\Robo\Collection\CollectionBuilder $task */
        $task = $this->task(Task\CoverageReportHtmlTask::class);
        $task->setOptions($options);

        return $task;
    }

    /**
     * @param task-phpunit-coverage-report-xml-options $options
     *
     * @return \Sweetchuck\Robo\PHPUnit\Task\CoverageReportXmlTask&\Robo\Collection\CollectionBuilder
     */
    protected function taskPHPUnitCoverageReportXml(array $options = []): CollectionBuilder
    {
        $task = $this->task(Task\CoverageReportXmlTask::class);
        $task->setOptions($options);

        return $task;
    }

    /**
     * @param task-phpunit-list-groups-options $options
     *
     * @return \Sweetchuck\Robo\PHPUnit\Task\ListGroupsTask&\Robo\Collection\CollectionBuilder
     */
    protected function taskPHPUnitListGroups(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\PHPUnit\Task\ListGroupsTask&\Robo\Collection\CollectionBuilder $task */
        $task = $this->task(Task\ListGroupsTask::class);
        if ($this instanceof ContainerAwareInterface) {
            $task->setContainer($this->getContainer());
        }

        $task->setOptions($options);

        return $task;
    }
    
    /**
     * @param task-phpunit-list-suites-options $options
     *
     * @return \Sweetchuck\Robo\PHPUnit\Task\ListSuitesTask&\Robo\Collection\CollectionBuilder
     */
    protected function taskPHPUnitListSuites(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\PHPUnit\Task\ListSuitesTask&\Robo\Collection\CollectionBuilder $task */
        $task = $this->task(Task\ListSuitesTask::class);
        if ($this instanceof ContainerAwareInterface) {
            $task->setContainer($this->getContainer());
        }

        $task->setOptions($options);

        return $task;
    }
    
    /**
     * @param task-phpunit-list-tests-options $options
     *
     * @return \Sweetchuck\Robo\PHPUnit\Task\ListTestsTask&\Robo\Collection\CollectionBuilder
     */
    protected function taskPHPUnitListTests(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\PHPUnit\Task\ListTestsTask&\Robo\Collection\CollectionBuilder $task */
        $task = $this->task(Task\ListTestsTask::class);
        if ($this instanceof ContainerAwareInterface) {
            $task->setContainer($this->getContainer());
        }

        $task->setOptions($options);

        return $task;
    }

    /**
     * @param task-phpunit-list-tests-xml-options $options
     *
     * @return \Sweetchuck\Robo\PHPUnit\Task\ListTestsXmlTask&\Robo\Collection\CollectionBuilder
     */
    protected function taskPHPUnitListTestsXml(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\PHPUnit\Task\ListTestsXmlTask&\Robo\Collection\CollectionBuilder $task */
        $task = $this->task(Task\ListTestsXmlTask::class);
        if ($this instanceof ContainerAwareInterface) {
            $task->setContainer($this->getContainer());
        }

        $task->setOptions($options);

        return $task;
    }

    /**
     * @param task-phpunit-merge-coverage-php-task-options $options
     *
     * @return \Sweetchuck\Robo\PHPUnit\Task\MergeCoveragePhpTask&\Robo\Collection\CollectionBuilder
     */
    protected function taskPHPUnitMergeCoveragePhp(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\PHPUnit\Task\MergeCoveragePhpTask&\Robo\Collection\CollectionBuilder $task */
        $task = $this->task(Task\MergeCoveragePhpTask::class);
        $task->setOptions($options);

        return $task;
    }

    /**
     * @param task-phpunit-parse-configuration-xml-options $options
     *
     * @return \Sweetchuck\Robo\PHPUnit\Task\ParseConfigurationXmlTask&\Robo\Collection\CollectionBuilder
     */
    protected function taskPHPUnitParseConfigurationXml(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\PHPUnit\Task\ParseConfigurationXmlTask&\Robo\Collection\CollectionBuilder $task */
        $task = $this->task(Task\ParseConfigurationXmlTask::class);
        $task->setOptions($options);

        return $task;
    }

    /**
     * @param task-phpunit-run-options $options
     *
     * @return \Sweetchuck\Robo\PHPUnit\Task\RunTask&\Robo\Collection\CollectionBuilder
     */
    protected function taskPHPUnitRun(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\PHPUnit\Task\RunTask&\Robo\Collection\CollectionBuilder $task */
        $task = $this->task(Task\RunTask::class);
        if ($this instanceof ContainerAwareInterface) {
            $task->setContainer($this->getContainer());
        }

        if ($this instanceof OutputAwareInterface) {
            $task->setOutput($this->output());
        }

        $task->setOptions($options);

        return $task;
    }

    /**
     * @param task-phpunit-test-cases-to-csv-options $options
     *
     * @return \Sweetchuck\Robo\PHPUnit\Task\TestCasesToCsvTask&\Robo\Collection\CollectionBuilder
     */
    protected function taskPHPUnitTestCasesToCsv(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\PHPUnit\Task\TestCasesToCsvTask&\Robo\Collection\CollectionBuilder $task */
        $task = $this->task(Task\TestCasesToCsvTask::class);
        if ($this instanceof OutputAwareInterface) {
            $task->setOutput($this->output());
        }

        $task->setOptions($options);

        return $task;
    }

    /**
     * @param task-phpunit-test-cases-to-filenames-options $options
     *
     * @return \Sweetchuck\Robo\PHPUnit\Task\TestCasesToFileNamesTask&\Robo\Collection\CollectionBuilder
     */
    protected function taskPHPUnitTestCasesToFileNames(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\PHPUnit\Task\TestCasesToFileNamesTask&\Robo\Collection\CollectionBuilder $task */
        $task = $this->task(Task\TestCasesToFileNamesTask::class);
        if ($this instanceof OutputAwareInterface) {
            $task->setOutput($this->output());
        }

        $task->setOptions($options);

        return $task;
    }
}
