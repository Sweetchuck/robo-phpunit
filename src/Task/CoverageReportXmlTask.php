<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Task;

use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Report\Clover as CloverReporter;

/**
 * @method null|\SebastianBergmann\CodeCoverage\CodeCoverage getCoverage()
 * @method $this setCoverage(\SebastianBergmann\CodeCoverage\CodeCoverage $coverage)
 *
 * @method null|\SebastianBergmann\CodeCoverage\Report\Clover getReporter()
 * @method $this setReporter(?\SebastianBergmann\CodeCoverage\Report\Clover $reporter)
 *
 * @method null|string getDstFile()
 * @method $this setDstFile(?string $dstFile)
 */
class CoverageReportXmlTask extends BaseTask
{

    protected string $taskName = 'PHPUnit - Coverage report in XML format';

    protected ?CodeCoverage $coverage = null;

    protected function initOptions(): static
    {
        parent::initOptions();
        $this->options += [
            'coverage' => [
                'type' => 'other',
                'value' => null,
            ],
            'reporter' => [
                'type' => 'other',
                'value' => null,
            ],
            'dstFile' => [
                'type' => 'other',
                'value' => null,
            ],
        ];

        return $this;
    }

    protected function getReporterFallback(): CloverReporter
    {
        return $this->getReporter() ?: $this->getReporterDefault();
    }

    protected function getReporterDefault(): CloverReporter
    {
        return new CloverReporter();
    }

    protected function runDoIt(): static
    {
        $reporter = $this->getReporterFallback();
        $this->assets['phpunit.coverage_merged.xml'] = $reporter->process($this->getCoverage(), $this->getDstFile());

        return $this;
    }
}
