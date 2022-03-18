<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit;

use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Filter as CodeCoverageFilter;
use SebastianBergmann\CodeCoverage\Report\PHP as PhpReporter;
use Symfony\Component\Console\Output\OutputInterface;

class CoveragePhpMerger
{
    protected ?CodeCoverage $coverage = null;

    protected ?CoverageDriverFactory $coverageDriverFactory = null;

    public function getCoverageDriverFactory(): ?CoverageDriverFactory
    {
        return $this->coverageDriverFactory;
    }

    /**
     * @return $this
     */
    public function setCoverageDriverFactory(?CoverageDriverFactory $coverageDriverFactory)
    {
        $this->coverageDriverFactory = $coverageDriverFactory;

        return $this;
    }

    public function getCoverage(): ?CodeCoverage
    {
        return $this->coverage;
    }

    /**
     * @return $this
     */
    public function mergeFiles(\Iterator $files, OutputInterface $writer)
    {
        return $this
            ->start()
            ->addFiles($files)
            ->finish($writer);
    }

    /**
     * @return $this
     */
    public function start()
    {
        $this->startCoverage();

        return $this;
    }

    /**
     * @return $this
     */
    protected function startCoverage()
    {
        $factory = $this->getCoverageDriverFactory() ?: new CoverageDriverFactory();
        $filter = new CodeCoverageFilter();
        $driver = $factory->createInstance($filter);
        $this->coverage = new CodeCoverage($driver, $filter);

        return $this;
    }

    /**
     * @return $this
     */
    public function addFiles(\Iterator $files)
    {
        while ($files->valid()) {
            $this->addFile($files->current());
            $files->next();
        }

        return $this;
    }

    /**
     * @param string|\SplFileInfo $file
     *
     * @return $this
     */
    public function addFile($file)
    {
        $filename = $file instanceof \SplFileInfo ? $file->getPathname() : rtrim($file, "\r\n");
        $filename = preg_replace(
            '@^/proc/self/fd/(?P<id>\d+)$@',
            'php://fd/$1',
            $filename,
        );

        if ($filename === '') {
            return $this;
        }

        $coverage = call_user_func(
            function ($filename) {
                return require $filename;
            },
            $filename,
        );
        if (!is_object($coverage) || !($coverage instanceof CodeCoverage)) {
            throw new \RuntimeException("$filename doesn't return a valid " . CodeCoverage::class . ' object!');
        }

        $this->normalizeCoverage($coverage);
        $this->coverage->merge($coverage);

        return $this;
    }

    /**
     * @return $this
     */
    public function finish(OutputInterface $writer)
    {
        $reporter = new PhpReporter();
        $writer->write($reporter->process($this->coverage));

        return $this;
    }

    /**
     * @return $this
     */
    protected function normalizeCoverage(CodeCoverage $coverage)
    {
        $tests = $coverage->getTests();
        foreach ($tests as &$test) {
            $test['fromTestcase'] = $test['fromTestcase'] ?? false;
        }
        $coverage->setTests($tests);

        return $this;
    }
}
