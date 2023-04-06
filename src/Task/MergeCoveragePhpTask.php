<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Task;

use SebastianBergmann\CodeCoverage\CodeCoverage;
use Sweetchuck\Robo\PHPUnit\CoveragePhpMerger;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method null|\Iterator getFiles()
 * @method $this setFiles(?\Iterator $value)
 *
 * @method null|\Symfony\Component\Console\Output\OutputInterface getWriter()
 * @method $this setWriter(?\Symfony\Component\Console\Output\OutputInterface $writer)
 *
 * @method null|\Sweetchuck\Robo\PHPUnit\CoveragePhpMerger getMerger()
 * @method $this setMerger(?\Sweetchuck\Robo\PHPUnit\CoveragePhpMerger $merger)
 */
class MergeCoveragePhpTask extends BaseTask
{

    protected string $taskName = 'PHPUnit - Merge coverage report PHP files';

    protected ?CodeCoverage $coverage = null;

    protected function initOptions(): static
    {
        parent::initOptions();
        $this->options += [
            'files' => [
                'type' => 'other',
                'value' => null,
            ],
            'merger' => [
                'type' => 'other',
                'value' => null,
            ],
            'writer' => [
                'type' => 'other',
                'value' => null,
            ],
        ];

        return $this;
    }

    protected function getFilesFallback(): \Iterator
    {
        return $this->getFiles() ?: new \ArrayIterator([]);
    }

    protected function getMergerFallback(): CoveragePhpMerger
    {
        return $this->getMerger() ?: new CoveragePhpMerger();
    }

    protected function getWriterFallback(): OutputInterface
    {
        return $this->getWriter() ?: new NullOutput();
    }

    protected function runDoIt(): static
    {
        $files = $this->getFilesFallback();
        $merger = $this->getMergerFallback();
        $writer = $this->getWriterFallback();
        $merger->mergeFiles($files, $writer);
        $this->assets['phpunit.merged_coverage'] = $merger->getCoverage();

        return $this;
    }

    protected function runInitAssets(): static
    {
        return $this;
    }

    protected function runProcessOutputs(): static
    {
        return $this;
    }
}
