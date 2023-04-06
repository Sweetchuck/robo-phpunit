<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Test\Unit;

use Codeception\Attribute\DataProvider;
use SebastianBergmann\CodeCoverage\Driver\PcovDriver;
use SebastianBergmann\CodeCoverage\Driver\XdebugDriver;
use SebastianBergmann\CodeCoverage\Driver\Xdebug3Driver;
use SebastianBergmann\CodeCoverage\Filter as CodeCoverageFilter;
use Sweetchuck\Robo\PHPUnit\CoverageDriverFactory;
use Codeception\Test\Unit;
use Sweetchuck\Robo\PHPUnit\Test\UnitTester;
use Sweetchuck\Robo\PHPUnit\Utils;

/**
 * @covers \Sweetchuck\Robo\PHPUnit\CoverageDriverFactory
 */
class CoverageDriverFactoryTest extends Unit
{

    protected UnitTester $tester;

    /**
     * @return array<string, mixed>
     */
    public function casesCreateInstance(): array
    {
        $cases = [
            'pcov' => [
                PcovDriver::class,
                [
                    'pcov' => 0,
                    'xdebug' => 1,
                    'phpdbg' => 2,
                ],
            ],
        ];

        switch (Utils::phpunitVersionMajor()) {
            case 9:
                $cases['xdebug-phpunit-09'] = [
                    Xdebug3Driver::class,
                    [
                        'pcov' => 1,
                        'xdebug' => 0,
                        'phpdbg' => 2,
                    ],
                ];
                break;

            case 10:
                $cases['xdebug-phpunit-10'] = [
                    XdebugDriver::class,
                    [
                        'pcov' => 1,
                        'xdebug' => 0,
                        'phpdbg' => 2,
                    ],
                ];
                break;
        }


        return $cases;
    }

    /**
     * @param array<string, int> $precedenceList
     */
    #[DataProvider('casesCreateInstance')]
    public function testCreateInstance(string $expected, array $precedenceList): void
    {
        $filter = new CodeCoverageFilter();
        $factory = new CoverageDriverFactory();
        $factory->setPrecedenceList($precedenceList);
        $driver = $factory->createInstance($filter);

        $this->tester->assertInstanceOf($expected, $driver);
    }
}
