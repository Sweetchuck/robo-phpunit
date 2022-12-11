<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit;

use Symfony\Component\Filesystem\Path;

class PHPUnitConfigurationXmlParser
{
    protected string $baseDir;

    protected \DOMDocument $xml;

    protected \DOMXPath $xpath;

    /**
     * @var phpunit-cofiguration-array
     */
    protected array $phpunit = [];

    /**
     * @return phpunit-cofiguration-array
     */
    public function parse(string $content, string $baseDir = ''): array
    {
        return $this
            ->init($content, $baseDir)
            ->parseLogging()
            ->parseCoverage()
            ->phpunit;
    }

    /**
     * @return $this
     */
    protected function init(string $content, string $baseDir)
    {
        $this->baseDir = $baseDir ?: '.';
        $this->phpunit = [
            'logging' => [],
            'logging.directories' => [],
        ];
        $this->xml = new \DOMDocument();
        $this->xml->loadXML($content);
        $this->xpath = new \DOMXPath($this->xml);

        return $this;
    }

    /**
     * @todo Support for every kind of logging.
     *
     * @return $this
     */
    protected function parseLogging()
    {
        $logs = $this->xpath->query('/phpunit/logging/*[@target or @outputFile or @outputDirectory]');
        if ($logs === false) {
            return $this;
        }

        /** @var \DOMElement $log */
        foreach ($logs as $log) {
            $type = $log->hasAttribute('type') ? $log->getAttribute('type') : $log->tagName;
            $path = $log->getAttribute('target')
                ?: $log->getAttribute('outputFile')
                    ?: $log->getAttribute('outputDirectory');
            $this->addItemToLogging($type, $path);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function parseCoverage()
    {
        $logs = $this->xpath->query('/phpunit/coverage/report/*[@outputFile or @outputDirectory]');
        if ($logs === false) {
            return $this;
        }

        /** @var \DOMElement $log */
        foreach ($logs as $log) {
            $type = 'coverage-' . $log->tagName;
            $path = $log->getAttribute('outputFile') ?: $log->getAttribute('outputDirectory');
            $this->addItemToLogging($type, $path);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function addItemToLogging(string $type, string $path)
    {
        $fileLoggingTypes = $this->getFileLoggingTypes();
        $this->phpunit['logging'][$type] = $path;

        $urlScheme = parse_url($path, PHP_URL_SCHEME) ?: 'file';
        if ($urlScheme !== 'file') {
            return $this;
        }

        $directory = in_array($type, $fileLoggingTypes) ? Path::getDirectory($path) : $path;
        if (Path::isRelative($directory)) {
            $directory = Path::join($this->baseDir, $directory);
        }

        $this->phpunit['logging.directories'][$type] = $directory;

        return $this;
    }

    /**
     * @return array<string>
     */
    protected function getFileLoggingTypes(): array
    {
        return [
            'coverage-text',
            'coverage-clover',
            'coverage-xml',
            'json',
            'plain',
            'tap',
            'junit',
            'testdox-html',
            'testdoxHtml',
            'testdox-text',
        ];
    }
}
