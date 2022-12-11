<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Task;

use Sweetchuck\Robo\PHPUnit\PHPUnitConfigurationXmlParser;
use Sweetchuck\Robo\PHPUnit\Utils;
use Symfony\Component\Filesystem\Path;

class ParseConfigurationXmlTask extends BaseTask
{
    protected string $taskName = 'PHPUnit - Parse configuration XML';

    protected string $phpunitXmlParserClass = PHPUnitConfigurationXmlParser::class;

    /**
     * @var null|string|resource
     */
    protected $xmlFile = null;

    /**
     * @return null|string|resource
     */
    public function getXmlFile()
    {
        return $this->xmlFile;
    }

    /**
     * @param string|resource $value
     *   XML content, file name, stream resource.
     *
     * @return $this
     */
    public function setXmlFile($value)
    {
        $this->xmlFile = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function initOptions()
    {
        parent::initOptions();
        $this->options += [
            'xmlFile' => [
                'type' => 'other',
                'value' => 'phpunit.xml.dist',
            ],
        ];

        return $this;
    }

    protected function runDoIt()
    {
        $xmlString = $this->getXmlString();
        if (!$xmlString) {
            $workingDirectory = $this->getWorkingDirectory();
            $xmlFile = (string) $this->getXmlFile();

            $this->processExitCode = 1;
            $this->processStdError = implode(PHP_EOL, [
                'Problem with the file content reading.',
                "workingDirectory = '$workingDirectory'",
                "xmlFile = '$xmlFile'",
            ]);

            return $this;
        }

        /** @var \Sweetchuck\Robo\PHPUnit\PHPUnitConfigurationXmlParser $configurationXmlParser */
        $configurationXmlParser = new $this->phpunitXmlParserClass();
        $assets = $configurationXmlParser->parse($xmlString, $this->getWorkingDirectory());
        foreach ($assets as $key => $value) {
            $this->assets["phpunit.$key"] = $value;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function runInitAssets()
    {
        // Assets are already populated in the $this->runDoIt().
        return $this;
    }

    protected function getXmlString(): ?string
    {
        $xmlFile = $this->getXmlFile();
        if (is_resource($xmlFile)) {
            return Utils::getXmlStringFromResource($xmlFile);
        }

        $xmlFile = (string) $xmlFile;

        return Utils::isXmlString($xmlFile, 'phpunit') ?
            $xmlFile
            : $this->getXmlStringFromFile($xmlFile);
    }

    protected function getXmlStringFromFile(string $fileName): ?string
    {
        $workingDirectory = $this->getWorkingDirectory() ?: '.';

        if ($fileName) {
            if (Path::isRelative($fileName)) {
                $fileName = Path::join($workingDirectory, $fileName);
            }

            $content = file_get_contents($fileName);

            return $content !== false ? $content : null;
        }

        $fileNameSuggestions = [
            "$workingDirectory/phpunit.xml",
            "$workingDirectory/phpunit.xml.dist",
        ];

        foreach ($fileNameSuggestions as $fileNameSuggestion) {
            if (file_exists($fileNameSuggestion)) {
                $content = file_get_contents($fileNameSuggestion);

                return $content !== false ? $content : null;
            }
        }

        return null;
    }
}
