<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit;

use SebastianBergmann\CodeCoverage\Driver\Driver as CoverageDriver;
use SebastianBergmann\CodeCoverage\Driver\PcovDriver;
use SebastianBergmann\CodeCoverage\Driver\XdebugDriver;
use SebastianBergmann\CodeCoverage\Driver\Xdebug2Driver;
use SebastianBergmann\CodeCoverage\Driver\Xdebug3Driver;
use SebastianBergmann\CodeCoverage\Filter;
use SebastianBergmann\Environment\Runtime;

/**
 * @see \SebastianBergmann\CodeCoverage\Driver\Selector
 */
class CoverageDriverFactory
{

    /**
     * @var array<string, int>
     */
    protected array $precedenceList = [
        'pcov' => 0,
        'xdebug' => 1,
        'phpdbg' => 2,
    ];

    /**
     * @return array<string, int>
     */
    public function getPrecedenceList(): array
    {
        return $this->precedenceList;
    }

    /**
     * @param int[] $precedenceList
     */
    public function setPrecedenceList(array $precedenceList): static
    {
        asort($precedenceList);
        $this->precedenceList = $precedenceList;

        return $this;
    }

    public function createInstance(Filter $filter): ?CoverageDriver
    {
        $precedenceList = array_keys($this->getPrecedenceList());
        $driver = null;
        while (!$driver && $precedenceList) {
            $precedence = array_shift($precedenceList);
            switch ($precedence) {
                case 'pcov':
                    $driver = $this->createInstancePcov($filter);
                    break;

                case 'xdebug':
                    $driver = $this->createInstanceXdebug($filter);
                    break;

                case 'phpdbg':
                    $driver = $this->createInstancePhpdbg();
                    break;
            }
        }

        return $driver;
    }

    public function createInstancePcov(Filter $filter): ?PcovDriver
    {
        if (!(new Runtime())->hasPCOV()) {
            return null;
        }

        if (class_exists(PcovDriver::class)) {
            return new PcovDriver($filter);
        }

        throw new \Exception('not supported phpunit version');
    }

    /**
     * @return null|\SebastianBergmann\CodeCoverage\Driver\Xdebug2Driver|\SebastianBergmann\CodeCoverage\Driver\Xdebug3Driver|\SebastianBergmann\CodeCoverage\Driver\XdebugDriver
     */
    public function createInstanceXdebug(Filter $filter)
    {
        if (!(new Runtime())->hasXdebug()) {
            return null;
        }

        $xdebugVersion = phpversion('xdebug') ?: '3.0.0';
        $driver = version_compare($xdebugVersion, '3', '>=') ?
            $this->createInstanceXdebug3($filter)
            : new Xdebug2Driver($filter);

        $driver->enableDeadCodeDetection();

        return $driver;
    }

    /**
     * @return \SebastianBergmann\CodeCoverage\Driver\Xdebug3Driver|\SebastianBergmann\CodeCoverage\Driver\XdebugDriver
     */
    protected function createInstanceXdebug3(Filter $filter)
    {
        // PHPUnit 9.x vs 10.x.
        return class_exists(Xdebug3Driver::class) ?
            new Xdebug3Driver($filter)
            : new XdebugDriver($filter);
    }

    public function createInstancePhpdbg(): ?PhpdbgDriver
    {
        if (!(new Runtime())->hasPHPDBGCodeCoverage()) {
            return null;
        }

        return new PhpdbgDriver();
    }
}
