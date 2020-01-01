<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Task;

/**
 * @method string getCoverageClover()
 * @method $this  setCoverageClover(string $path)
 * @method string getCoverageCrap4j()
 * @method $this  setCoverageCrap4j(string $path)
 * @method string getCoverageHtml()
 * @method $this  setCoverageHtml(string $path)
 * @method string getCoveragePhp()
 * @method $this  setCoveragePhp(string $path)
 * @method string getCoverageText()
 * @method $this  setCoverageText(string $path)
 * @method string getCoverageXml()
 * @method $this  setCoverageXml(string $path)
 * @method string getWhitelist()
 * @method $this  setWhitelist(string $path)
 * @method bool   getDisableCoverageIgnore()
 * @method $this  setDisableCoverageIgnore(bool $disable)
 * @method string getLogJUnit()
 * @method $this  setLogJUnit(string $path)
 * @method string getLogTeamCity()
 * @method $this  setLogTeamCity(string $path)
 * @method string getTestdoxHtml()
 * @method $this  setTestdoxHtml(string $path)
 * @method string getTestdoxText()
 * @method $this  setTestdoxText(string $path)
 * @method string getTestdoxXml()
 * @method $this  setTestdoxXml(string $path)
 * @method bool   getReverseList()
 * @method $this  setReverseList(bool $isReverse)
 * @method array  getFilter()
 * @method $this  setFilter(array $paths)
 * @method array  getTestSuite()
 * @method $this  setTestSuite(array $testSuiteNames)
 * @method array  getGroup()
 * @method $this  setGroup(array $groupNames)
 * @method array  getExcludeGroup()
 * @method $this  setExcludeGroup(array $groupNames)
 * @method string getTestSuffix()
 * @method $this  setTestSuffix(string $path)
 * @method bool   getDoNotReportUselessTests()
 * @method $this  setDoNotReportUselessTests(string $doNotReport)
 * @method bool   getStrictCoverage()
 * @method $this  setStrictCoverage(bool $strictCoverage)
 * @method bool   getStrictGlobalState()
 * @method $this  setStrictGlobalState(bool $strictGlobalState)
 * @method bool   getDisallowTestOutput()
 * @method $this  setDisallowTestOutput(bool $disallowTestOutput)
 * @method bool   getDisallowResourceUsage()
 * @method $this  setDisallowResourceUsage(bool $disallowResourceUsage)
 * @method bool   getEnforceTimeLimit()
 * @method $this  setEnforceTimeLimit(bool $enforceTimeLimit)
 * @method bool   getDisallowTodoTests()
 * @method $this  setDisallowTodoTests(bool $disallowTodoTests)
 * @method bool   getProcessIsolation()
 * @method $this  setProcessIsolation(bool $processIsolation)
 * @method bool   getGlobalsBackup()
 * @method $this  setGlobalsBackup(bool $globalsBackup)
 * @method bool   getStaticBackup()
 * @method $this  setStaticBackup(bool $staticBackup)
 * @method string getColors()
 * @method $this  setColors(string $when)
 * @method int|string getColumns()
 * @method $this  setColumns(int|string $columns)
 * @method bool   getStderr()
 * @method $this  setStderr(bool $redirectOutputToStdError)
 * @method bool   getStopOnError()
 * @method $this  setStopOnError(bool $stop)
 * @method bool   getStopOnFailure()
 * @method $this  setStopOnFailure(bool $stop)
 * @method bool   getStopOnWarning()
 * @method $this  setStopOnWarning(bool $stop)
 * @method bool   getStopOnRisky()
 * @method $this  setStopOnRisky(bool $stop)
 * @method bool   getStopOnSkipped()
 * @method $this  setStopOnSkipped(bool $stop)
 * @method bool   getStopOnIncomplete()
 * @method $this  setStopOnIncomplete(bool $stop)
 * @method bool   getFailOnWarning()
 * @method $this  setFailOnWarning(bool $stop)
 * @method bool   getFailOnRisky()
 * @method $this  setFailOnRisky(bool $stop)
 * @method bool   getVerbose()
 * @method $this  setVerbose(bool $isVerbose)
 * @method bool   getDebug()
 * @method $this  setDebug(string $isDebug)
 * @method string getLoader()
 * @method $this  setLoader(string $path)
 * @method int    getRepeat()
 * @method $this  setRepeat(int $times)
 * @method bool   getTeamCity()
 * @method $this  setTeamCity(bool $isTeamCityFormat)
 * @method bool   getTestdox()
 * @method $this  setTestdox(bool $isTestdoxFormat)
 * @method array  getTestdoxGroup()
 * @method $this  setTestdoxGroup(array $groupNames)
 * @method array  getTestdoxExcludeGroup()
 * @method $this  setTestdoxExcludeGroup(array $groupNames)
 * @method string getPrinter()
 * @method $this  setPrinter(string $className)
 * @method string getBootstrap()
 * @method $this  setBootstrap(string $path)
 * @method bool   getNoCoverage()
 * @method $this  setNoCoverage(bool $noCoverage)
 * @method bool   getNoLogging()
 * @method $this  setNoLogging(bool $noLogging)
 * @method bool   getNoExtensions()
 * @method $this  setNoExtensions(bool $noExtensions)
 * @method array  getIncludePath()
 * @method $this  setIncludePath(array $paths)
 */
class RunTask extends BaseCliTask
{
    /**
     * {@inheritdoc}
     */
    protected $taskName = 'PHPUnit - Run';

    /**
     * {@inheritdoc}
     */
    protected $outputParserClass = null;

    /**
     * {@inheritdoc}
     */
    protected $outputParserAssetNameMapping = [];

    /**
     * {@inheritdoc}
     */
    protected function initOptions()
    {
        $w = 0;
        parent::initOptions();
        $this->options += [
            'coverageClover' => [
                'type' => 'option:value',
                'value' => null,
                'weight' => $this->optionGroupWeights['coverage'] + $w++,
            ],
            'coverageCrap4j' => [
                'type' => 'option:value',
                'value' => null,
                'weight' => $this->optionGroupWeights['coverage'] + $w++,
            ],
            'coverageHtml' => [
                'type' => 'option:value',
                'value' => null,
                'weight' => $this->optionGroupWeights['coverage'] + $w++,
            ],
            'coveragePhp' => [
                'type' => 'option:value',
                'value' => null,
                'weight' => $this->optionGroupWeights['coverage'] + $w++,
            ],
            'coverageText' => [
                'type' => 'option:value',
                'value' => null,
                'weight' => $this->optionGroupWeights['coverage'] + $w++,
            ],
            'coverageXml' => [
                'type' => 'option:value',
                'value' => null,
                'weight' => $this->optionGroupWeights['coverage'] + $w++,
            ],
            'whitelist' => [
                'type' => 'option:value',
                'value' => null,
                'weight' => $this->optionGroupWeights['coverageOther'] + $w++,
            ],
            'disableCoverageIgnore' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['coverageOther'] + $w++,
            ],
            'logJUnit' => [
                'cliName' => 'log-junit',
                'type' => 'option:value',
                'value' => null,
                'weight' => $this->optionGroupWeights['logging'] + $w++,
            ],
            'logTeamCity' => [
                'cliName' => 'log-teamcity',
                'type' => 'option:value',
                'value' => null,
                'weight' => $this->optionGroupWeights['logging'] + $w++,
            ],
            'testdoxHtml' => [
                'type' => 'option:value',
                'value' => null,
                'weight' => $this->optionGroupWeights['logging'] + $w++,
            ],
            'testdoxText' => [
                'type' => 'option:value',
                'value' => null,
                'weight' => $this->optionGroupWeights['logging'] + $w++,
            ],
            'testdoxXml' => [
                'type' => 'option:value',
                'value' => null,
                'weight' => $this->optionGroupWeights['logging'] + $w++,
            ],
            'reverseList' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['logging'] + $w++,
            ],
            'filter' => [
                'type' => 'option:value:list',
                'value' => [],
                'weight' => $this->optionGroupWeights['testSelection'] + $w++,
            ],
            'testSuite' => [
                'cliName' => 'testsuite',
                'type' => 'option:value:list',
                'value' => [],
                'weight' => $this->optionGroupWeights['testSelection'] + $w++,
            ],
            'group' => [
                'type' => 'option:value:list',
                'value' => [],
                'weight' => $this->optionGroupWeights['testSelection'] + $w++,
            ],
            'excludeGroup' => [
                'type' => 'option:value:list',
                'value' => [],
                'weight' => $this->optionGroupWeights['testSelection'] + $w++,
            ],
            'testSuffix' => [
                'type' => 'option:value:list',
                'value' => [],
                'weight' => $this->optionGroupWeights['testSelection'] + $w++,
            ],
            'doNotReportUselessTests' => [
                'cliName' => 'dont-report-useless-tests',
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'strictCoverage' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'strictGlobalState' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'disallowTestOutput' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'disallowResourceUsage' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'enforceTimeLimit' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'disallowTodoTests' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'processIsolation' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'globalsBackup' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'staticBackup' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'colors' => [
                'type' => 'option:value',
                'value' => null,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'columns' => [
                'type' => 'option:value',
                'value' => null,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'stderr' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'stopOnError' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'stopOnFailure' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'stopOnWarning' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'stopOnRisky' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'stopOnSkipped' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'stopOnIncomplete' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'failOnWarning' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'failOnRisky' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'verbose' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'debug' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'loader' => [
                'type' => 'option:value',
                'value' => null,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'repeat' => [
                'type' => 'option:value',
                'value' => null,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'teamCity' => [
                'cliName' => 'teamcity',
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'testdox' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'testdoxGroup' => [
                'type' => 'option:value:list',
                'value' => [],
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'testdoxExcludeGroup' => [
                'type' => 'option:value:list',
                'value' => [],
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'printer' => [
                'type' => 'option:value',
                'value' => null,
                'weight' => $this->optionGroupWeights['testExecution'] + $w++,
            ],
            'bootstrap' => [
                'type' => 'option:value',
                'value' => null,
                'weight' => $this->optionGroupWeights['configuration'] + $w++,
            ],
            'noCoverage' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['configuration'] + $w++,
            ],
            'noLogging' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['configuration'] + $w++,
            ],
            'noExtensions' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['configuration'] + $w++,
            ],
            'includePath' => [
                'type' => 'option:value:list',
                'value' => [],
                'weight' => $this->optionGroupWeights['configuration'] + $w,
            ],
            // @todo Option -d key[=value].
        ];

        return $this;
    }
}
