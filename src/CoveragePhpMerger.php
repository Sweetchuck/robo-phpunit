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

    public function setCoverageDriverFactory(?CoverageDriverFactory $coverageDriverFactory): static
    {
        $this->coverageDriverFactory = $coverageDriverFactory;

        return $this;
    }

    public function getCoverage(): ?CodeCoverage
    {
        return $this->coverage;
    }

    public function mergeFiles(\Iterator $files, OutputInterface $writer): static
    {
        return $this
            ->start()
            ->addFiles($files)
            ->finish($writer);
    }

    public function start(): static
    {
        $this->startCoverage();

        return $this;
    }

    protected function startCoverage(): static
    {
        $factory = $this->getCoverageDriverFactory() ?: new CoverageDriverFactory();
        $filter = new CodeCoverageFilter();
        $driver = $factory->createInstance($filter);
        $this->coverage = new CodeCoverage($driver, $filter);

        return $this;
    }

    public function addFiles(\Iterator $files): static
    {
        while ($files->valid()) {
            $this->addFile($files->current());
            $files->next();
        }

        return $this;
    }

    /**
     * @param string|\SplFileInfo $file
     */
    public function addFile($file): static
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

    public function finish(OutputInterface $writer): static
    {
        $reporter = new PhpReporter();
        $writer->write($reporter->process($this->coverage));

        return $this;
    }

    protected function normalizeCoverage(CodeCoverage $coverage): static
    {
        $tests = $coverage->getTests();
        foreach ($tests as &$test) {
            $test['fromTestcase'] = $test['fromTestcase'] ?? false;
        }
        $coverage->setTests($tests);

        return $this;
    }
}
