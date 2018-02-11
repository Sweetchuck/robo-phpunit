<?php

namespace Sweetchuck\Robo\PHPUnit;

use Webmozart\PathUtil\Path;

class PHPUnitXmlParser
{
    /**
     * @var
     */
    protected $baseDir;

    /**
     * @var \DOMDocument
     */
    protected $xml;

    /**
     * @var \DOMXPath
     */
    protected $xpath;

    protected $phpunit = [];

    public function parse(string $content, string $baseDir = ''): array
    {
        return $this
            ->init($content, $baseDir)
            ->parseLogging()
            ->phpunit;
    }

    /**
     * @return $this
     */
    protected function init(string $content, string $baseDir)
    {
        $this->baseDir = $baseDir ?: '.';
        $this->phpunit = [];
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
        $this->phpunit['logging'] = [];
        $this->phpunit['logging.directories'] = [];

        $fileLoggingTypes = $this->getFileLoggingTypes();
        $logs = $this->xpath->query('/phpunit/logging/log');
        /** @var \DOMElement $log */
        foreach ($logs as $log) {
            if (!$log->hasAttribute('type') || !$log->hasAttribute('target')) {
                continue;
            }

            $type = $log->getAttribute('type');
            $target = $log->getAttribute('target');
            $this->phpunit['logging'][$type] = $target;

            $urlScheme = parse_url($target, PHP_URL_SCHEME) ?: 'file';
            if ($urlScheme === 'file') {
                $directory = in_array($type, $fileLoggingTypes) ?
                    Path::getDirectory($target)
                    : $target;

                if (Path::isRelative($directory)) {
                    $directory = Path::join($this->baseDir, $directory);
                }

                $this->phpunit['logging.directories'][$type] = $directory;
            }
        }

        return $this;
    }

    protected function getFileLoggingTypes(): array
    {
        return [
            'coverage-text',
            'coverage-clover',
            'json',
            'plain',
            'tap',
            'junit',
            'testdox-html',
            'testdox-text',
        ];
    }
}
