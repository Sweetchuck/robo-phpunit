<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Tests\Unit\Task;

use Sweetchuck\CliCmdBuilder\CommandBuilder;

class RunTaskTest extends BaseCliTaskTestBase
{

    /**
     * {@inheritdoc}
     */
    protected function initTask()
    {
        $this->task = $this->taskBuilder->taskPHPUnitRun();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function casesGetCommand(): array
    {
        return [
            'All-in-one' => $this->casesGetCommandBuild([
                [
                    'expected' => "cd 'a/b' &&",
                    'options' => [
                        'workingDirectory' => 'a/b',
                    ],
                ],
                [
                    'expected' => "FOO_BAR='abc'",
                    'options' => [
                        'envVars' => ['FOO_BAR' => 'abc'],
                    ],
                ],
                [
                    'expected' => 'my-phpdbg -qrr -b',
                    'options' => [
                        'phpExecutable' => 'my-phpdbg -qrr -b',
                    ],
                ],
                [
                    'expected' => "'my-phpunit'",
                    'options' => [
                        'phpunitExecutable' => 'my-phpunit',
                    ],
                ],
                // Code Coverage Options.
                [
                    'expected' => "--coverage-clover='a'",
                    'options' => [
                        'coverageClover' => 'a',
                    ],
                ],
                [
                    'expected' => "--coverage-crap4j='b'",
                    'options' => [
                        'coverageCrap4j' => 'b',
                    ],
                ],
                [
                    'expected' => "--coverage-html='c'",
                    'options' => [
                        'coverageHtml' => 'c',
                    ],
                ],
                [
                    'expected' => "--coverage-php='d'",
                    'options' => [
                        'coveragePhp' => 'd',
                    ],
                ],
                [
                    'expected' => "--coverage-text='e'",
                    'options' => [
                        'coverageText' => 'e',
                    ],
                ],
                [
                    'expected' => "--coverage-xml='f'",
                    'options' => [
                        'coverageXml' => 'f',
                    ],
                ],
                [
                    'expected' => "--whitelist='g'",
                    'options' => [
                        'whitelist' => 'g',
                    ],
                ],
                [
                    'expected' => '--disable-coverage-ignore',
                    'options' => [
                        'disableCoverageIgnore' => true,
                    ],
                ],
                // Logging Options.
                [
                    'expected' => "--log-junit='h'",
                    'options' => [
                        'logJUnit' => 'h',
                    ],
                ],
                [
                    'expected' => "--log-teamcity='i'",
                    'options' => [
                        'logTeamCity' => 'i',
                    ],
                ],
                [
                    'expected' => "--testdox-html='j'",
                    'options' => [
                        'testdoxHtml' => 'j',
                    ],
                ],
                [
                    'expected' => "--testdox-text='j'",
                    'options' => [
                        'testdoxText' => 'j',
                    ],
                ],
                [
                    'expected' => "--testdox-xml='j'",
                    'options' => [
                        'testdoxXml' => 'j',
                    ],
                ],
                [
                    'expected' => '--reverse-list',
                    'options' => [
                        'reverseList' => true,
                    ],
                ],
                // Test execution options.
                [
                    'expected' => '--dont-report-useless-tests',
                    'options' => [
                        'doNotReportUselessTests' => true,
                    ],
                ],
                [
                    'expected' => '--strict-coverage',
                    'options' => [
                        'strictCoverage' => true,
                    ],
                ],
                [
                    'expected' => '--strict-global-state',
                    'options' => [
                        'strictGlobalState' => true,
                    ],
                ],
                [
                    'expected' => '--disallow-test-output',
                    'options' => [
                        'disallowTestOutput' => true,
                    ],
                ],
                [
                    'expected' => '--disallow-resource-usage',
                    'options' => [
                        'disallowResourceUsage' => true,
                    ],
                ],
                [
                    'expected' => '--enforce-time-limit',
                    'options' => [
                        'enforceTimeLimit' => true,
                    ],
                ],
                [
                    'expected' => '--disallow-todo-tests',
                    'options' => [
                        'disallowTodoTests' => true,
                    ],
                ],
                [
                    'expected' => '--process-isolation',
                    'options' => [
                        'processIsolation' => true,
                    ],
                ],
                [
                    'expected' => '--globals-backup',
                    'options' => [
                        'globalsBackup' => true,
                    ],
                ],
                [
                    'expected' => '--static-backup',
                    'options' => [
                        'staticBackup' => true,
                    ],
                ],
                [
                    'expected' => "--colors='auto'",
                    'options' => [
                        'colors' => 'auto',
                    ],
                ],
                [
                    'expected' => "--columns='1'",
                    'options' => [
                        'columns' => 1,
                    ],
                ],
                [
                    'expected' => '--stderr',
                    'options' => [
                        'stderr' => true,
                    ],
                ],
                [
                    'expected' => '--stop-on-error',
                    'options' => [
                        'stopOnError' => true,
                    ],
                ],
                [
                    'expected' => '--stop-on-failure',
                    'options' => [
                        'stopOnFailure' => true,
                    ],
                ],
                [
                    'expected' => '--stop-on-warning',
                    'options' => [
                        'stopOnWarning' => true,
                    ],
                ],
                [
                    'expected' => '--stop-on-risky',
                    'options' => [
                        'stopOnRisky' => true,
                    ],
                ],
                [
                    'expected' => '--stop-on-skipped',
                    'options' => [
                        'stopOnSkipped' => true,
                    ],
                ],
                [
                    'expected' => '--stop-on-incomplete',
                    'options' => [
                        'stopOnIncomplete' => true,
                    ],
                ],
                [
                    'expected' => '--fail-on-warning',
                    'options' => [
                        'failOnWarning' => true,
                    ],
                ],
                [
                    'expected' => '--fail-on-risky',
                    'options' => [
                        'failOnRisky' => true,
                    ],
                ],
                [
                    'expected' => '--verbose',
                    'options' => [
                        'verbose' => true,
                    ],
                ],
                [
                    'expected' => '--debug',
                    'options' => [
                        'debug' => true,
                    ],
                ],
                [
                    'expected' => "--loader='w'",
                    'options' => [
                        'loader' => 'w',
                    ],
                ],
                [
                    'expected' => "--repeat='3'",
                    'options' => [
                        'repeat' => 3,
                    ],
                ],
                [
                    'expected' => '--teamcity',
                    'options' => [
                        'teamCity' => true,
                    ],
                ],
                [
                    'expected' => '--testdox',
                    'options' => [
                        'testdox' => true,
                    ],
                ],
                [
                    'expected' => "--testdox-group='x'",
                    'options' => [
                        'testdoxGroup' => ['x'],
                    ],
                ],
                [
                    'expected' => "--testdox-exclude-group='y'",
                    'options' => [
                        'testdoxExcludeGroup' => ['y'],
                    ],
                ],
                [
                    'expected' => "--printer='my-printer'",
                    'options' => [
                        'printer' => 'my-printer',
                    ],
                ],
                // Configuration options.
                [
                    'expected' => "--bootstrap='z'",
                    'options' => [
                        'bootstrap' => 'z',
                    ],
                ],
                [
                    'expected' => '--no-coverage',
                    'options' => [
                        'noCoverage' => true,
                    ],
                ],
                [
                    'expected' => '--no-logging',
                    'options' => [
                        'noLogging' => true,
                    ],
                ],
                [
                    'expected' => '--no-extensions',
                    'options' => [
                        'noExtensions' => true,
                    ],
                ],
                [
                    'expected' => "--include-path='aa,ac'",
                    'options' => [
                        'includePath' => ['aa' => true, 'ab' => false, 'ac' => true],
                    ],
                ],
                // Test Selection Options.
                [
                    'expected' => "--filter='n,p'",
                    'options' => [
                        'filter' => ['n' => true, 'o' => false, 'p' => true],
                    ],
                ],
                [
                    'expected' => "--testsuite='u,v'",
                    'options' => [
                        'testSuite' => ['u', 'v'],
                    ],
                ],
                [
                    'expected' => "--group='q,r'",
                    'options' => [
                        'group' => ['q', 'r'],
                    ],
                ],
                [
                    'expected' => "--exclude-group='m'",
                    'options' => [
                        'excludeGroup' => ['m'],
                    ],
                ],
                [
                    'expected' => "--test-suffix='s,t'",
                    'options' => [
                        'testSuffix' => ['s', 't'],
                    ],
                ],
            ]),
            'phpExecutable - cmd-builder-01' => $this->casesGetCommandBuild([
                [
                    'expected' => "A='b' C='d' my-php -d 'e=f' -d 'g=h' 'vendor/bin/phpunit'",
                    'options' => [
                        'phpExecutable' => (new CommandBuilder())
                            ->addEnvVar('A', 'b')
                            ->addEnvVar('C', 'd')
                            ->setExecutable('my-php')
                            ->addOption('-d', 'e=f')
                            ->addOption('-d', 'g=h')
                            ->setOutputType('unchanged'),
                    ],
                ],
            ]),
            'phpunitExecutable - cmd-builder-01' => $this->casesGetCommandBuild([
                [
                    'expected' => "A='b' C='d' my-phpunit",
                    'options' => [
                        'phpExecutable' => '',
                        'phpunitExecutable' => (new CommandBuilder())
                            ->addEnvVar('A', 'b')
                            ->addEnvVar('C', 'd')
                            ->setExecutable('my-phpunit')
                            ->setOutputType('unchanged'),
                    ],
                ],
            ]),
        ];
    }

    protected function casesGetCommandBuild(array $values): array
    {
        $args = [[], []];
        foreach ($values as $value) {
            $args[0][] = $value['expected'];
            $args[1] += $value['options'];
        }
        $args[0] = implode(' ', $args[0]);

        return $args;
    }

    /**
     * {@inheritdoc}
     */
    public function casesRunSuccess(): array
    {
        return [
            'basic' => [
                [
                    'exitCode' => 0,
                ],
                [
                    'exitCode' => 0,
                    'stdOutput' => '',
                    'stdError' => '',
                ],
            ],
        ];
    }

    public function testGetSetEnvVars(): void
    {
        $this->tester->assertSame([], $this->task->getEnvVars());

        $this->task->setEnvVars(['a' => 'b']);
        $this->tester->assertSame(['a' => 'b'], $this->task->getEnvVars());

        $this->task->addEnvVar('c', 'd');
        $this->tester->assertSame(['a' => 'b', 'c' => 'd'], $this->task->getEnvVars());

        $this->task->addEnvVars(['e' => 'f', 'g' => 'h']);
        $this->tester->assertSame(
            ['a' => 'b', 'c' => 'd', 'e' => 'f', 'g' => 'h'],
            $this->task->getEnvVars()
        );

        $this->task->removeEnvVar('e');
        $this->tester->assertSame(
            ['a' => 'b', 'c' => 'd', 'g' => 'h'],
            $this->task->getEnvVars()
        );

        $this->task->removeEnvVars(['a', 'g']);
        $this->tester->assertSame(
            ['c' => 'd'],
            $this->task->getEnvVars()
        );
    }
}
