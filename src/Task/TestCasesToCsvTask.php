<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Task;

use Sweetchuck\Robo\PHPUnit\TestCasesConverter;
use Sweetchuck\Robo\PHPUnit\Utils;

/**
 * @method string getXmlFile()
 * @method $this  setXmlFile(string|resource $xmlFile)
 *   JUnit XML content, file name, stream resource.
 *
 * @method string getFileNameRelativeTo()
 * @method $this  setFileNameRelativeTo(string $fileNameRelativeTo)
 *
 * @method array getBootstrapFiles()
 * @method $this setBootstrapFiles(array $bootstrapFiles)
 *
 * @method array getGranularity()
 * @method $this setGranularity(bool[] $granularity)
 *
 * @method array getCsvOptions()
 * @method $this setCsvOptions(array $csvOptions)
 *
 * @method bool  getWithHeader()
 * @method $this setWithHeader(bool $withHeader)
 */
class TestCasesToCsvTask extends BaseTask
{
    protected string $taskName = 'PHPUnit - Test cases to CSV';

    /**
     * {@inheritdoc}
     */
    protected function initOptions()
    {
        parent::initOptions();
        $this->options += [
            'xmlFile' => [
                'type' => 'other',
                'value' => '',
            ],
            'fileNameRelativeTo' => [
                'type' => 'other',
                'value' => '',
            ],
            'bootstrapFiles' => [
                'type' => 'other',
                'value' => [],
            ],
            'granularity' => [
                'type' => 'other',
                'value' => [],
            ],
            'withHeader' => [
                'type' => 'other',
                'value' => true,
            ],
            'csvOptions' => [
                'type' => 'other',
                'value' => [],
            ],
        ];

        return $this;
    }

    protected function runDoIt()
    {
        $xmlString = Utils::getXmlString(
            $this->getXmlFile(),
            'testsuites',
            $this->getWorkingDirectory(),
        );

        $converter = $this->createConverter();
        $this->assets['phpunit.tests.csv'] = $converter->toCsv(
            $xmlString,
            $this->getFileNameRelativeTo(),
            Utils::filterEnabled($this->getBootstrapFiles()),
            $this->getGranularity(),
            $this->getWithHeader(),
            $this->getCsvOptions(),
        );

        return $this;
    }

    protected function runInitAssets()
    {
        return $this;
    }

    protected function runProcessOutputs()
    {
        return $this;
    }

    protected function createConverter(): TestCasesConverter
    {
        return new TestCasesConverter();
    }
}
