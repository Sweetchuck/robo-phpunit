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
 */
class TestCasesToFileNamesTask extends BaseTask
{
    protected string $taskName = 'PHPUnit - Test cases to file names';

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
        $this->assets['phpunit.tests.fileNames'] = $converter->toFileNames(
            $xmlString,
            $this->getFileNameRelativeTo(),
            Utils::filterEnabled($this->getBootstrapFiles()),
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
