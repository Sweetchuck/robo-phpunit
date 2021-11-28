<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit;

use SebastianBergmann\CodeCoverage\Driver\Driver as CoverageDriver;
use SebastianBergmann\CodeCoverage\Driver\PCOV;
use SebastianBergmann\CodeCoverage\Driver\PcovDriver;
use SebastianBergmann\CodeCoverage\Driver\PhpdbgDriver;
use SebastianBergmann\CodeCoverage\Driver\Xdebug as XdebugDriver;
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
     * @var int[]
     */
    protected array $precedenceList = [
        'pcov' => 0,
        'xdebug' => 1,
        'phpdbg' => 2,
    ];

    /**
     * @return int[]
     */
    public function getPrecedenceList(): array
    {
        return $this->precedenceList;
    }

    /**
     * @param int[] $precedenceList
     *
     * @return $this
     */
    public function setPrecedenceList(array $precedenceList)
    {
        asort($precedenceList);
        $this->precedenceList = $precedenceList;

        return $this;
    }

    public function createInstance(?Filter $filter = null): ?CoverageDriver
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
                    $driver = $this->createInstancePhpdbg($filter);
                    break;
            }
        }

        return $driver;
    }

    public function createInstancePcov(?Filter $filter)
    {
        if (!(new Runtime())->hasPCOV()) {
            return null;
        }

        if (class_exists(PcovDriver::class)) {
            return new PcovDriver($filter);
        }

        if (class_exists(PCOV::class)) {
            return new PCOV();
        }

        throw new \Exception('not supported phpunit version');
    }

    public function createInstanceXdebug(?Filter $filter)
    {
        if (!(new Runtime())->hasXdebug()) {
            return null;
        }

        if (class_exists(XdebugDriver::class)) {
            return new XdebugDriver($filter);
        }

        $xdebugVersion = phpversion('xdebug');
        $driver = version_compare($xdebugVersion, '3', '>=') ?
            new Xdebug3Driver($filter)
            : new Xdebug2Driver($filter);

        $driver->enableDeadCodeDetection();

        return $driver;
    }

    public function createInstancePhpdbg(?Filter $filter)
    {
        if (!(new Runtime())->hasPHPDBGCodeCoverage()) {
            return null;
        }

        return new PhpdbgDriver();
    }
}
