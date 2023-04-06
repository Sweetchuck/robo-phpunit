<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Task;

use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Report\Html\Facade as HtmlReporter;
use Sweetchuck\Robo\PHPUnit\Utils;

/**
 * @method null|\SebastianBergmann\CodeCoverage\CodeCoverage getCoverage()
 * @method $this setCoverage(\SebastianBergmann\CodeCoverage\CodeCoverage $coverage)
 *
 * @method null|\SebastianBergmann\CodeCoverage\Report\Html\Facade getReporter()
 * @method $this setReporter(?\SebastianBergmann\CodeCoverage\Report\Html\Facade $reporter)
 */
class CoverageReportHtmlTask extends BaseTask
{

    protected string $taskName = 'PHPUnit - Coverage report in HTML format';

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
        ];

        return $this;
    }

    protected function getReporterFallback(): HtmlReporter
    {
        return $this->getReporter() ?: $this->getReporterDefault();
    }

    protected function getReporterDefault(): HtmlReporter
    {
        if (Utils::phpunitVersionMajor() === 9) {
            // @todo Configurable.
            $lowUpperBound = 50;
            $highLowerBound = 90;
            $generator = '';

            return new HtmlReporter($lowUpperBound, $highLowerBound, $generator);
        }

        return new HtmlReporter();
    }

    protected function runDoIt(): static
    {
        $reporter = $this->getReporterFallback();
        $reporter->process($this->getCoverage(), $this->getWorkingDirectory());

        return $this;
    }
}
