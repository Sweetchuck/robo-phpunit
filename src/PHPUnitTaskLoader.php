<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit;

use League\Container\ContainerAwareInterface;
use Robo\Collection\CollectionBuilder;
use Robo\Contract\OutputAwareInterface;

trait PHPUnitTaskLoader
{
    /**
     * @return \Sweetchuck\Robo\PHPUnit\Task\ListGroupsTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskPHPUnitListGroupsTask(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\PHPUnit\Task\ListGroupsTask|\Robo\Collection\CollectionBuilder $task */
        $task = $this->task(Task\ListGroupsTask::class);
        if ($this instanceof ContainerAwareInterface) {
            $task->setContainer($this->getContainer());
        }

        $task->setOptions($options);

        return $task;
    }
    
    /**
     * @return \Sweetchuck\Robo\PHPUnit\Task\ListSuitesTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskPHPUnitListSuitesTask(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\PHPUnit\Task\ListSuitesTask|\Robo\Collection\CollectionBuilder $task */
        $task = $this->task(Task\ListSuitesTask::class);
        if ($this instanceof ContainerAwareInterface) {
            $task->setContainer($this->getContainer());
        }

        $task->setOptions($options);

        return $task;
    }
    
    /**
     * @return \Sweetchuck\Robo\PHPUnit\Task\ListTestsTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskPHPUnitListTestsTask(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\PHPUnit\Task\ListTestsTask|\Robo\Collection\CollectionBuilder $task */
        $task = $this->task(Task\ListTestsTask::class);
        if ($this instanceof ContainerAwareInterface) {
            $task->setContainer($this->getContainer());
        }

        $task->setOptions($options);

        return $task;
    }

    /**
     * @return \Sweetchuck\Robo\PHPUnit\Task\ParseXmlTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskPHPUnitParseXml(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\PHPUnit\Task\ParseXmlTask|\Robo\Collection\CollectionBuilder $task */
        $task = $this->task(Task\ParseXmlTask::class);
        $task->setOptions($options);

        return $task;
    }

    /**
     * @return \Sweetchuck\Robo\PHPUnit\Task\RunTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskPHPUnitRun(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\PHPUnit\Task\RunTask|\Robo\Collection\CollectionBuilder $task */
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
}
