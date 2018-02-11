<?php

namespace Sweetchuck\Robo\PHPUnit\Task;

use Psr\Http\Message\StreamInterface;
use Sweetchuck\Robo\PHPUnit\PHPUnitXmlParser;
use Webmozart\PathUtil\Path;

class ParseXmlTask extends BaseTask
{
    /**
     * {@inheritdoc}
     */
    protected $taskName = 'PHPUnit - Parse XML';

    /**
     * @var string
     */
    protected $phpunitXmlParserClass = PHPUnitXmlParser::class;

    // region Options

    // region xmlFile
    /**
     * @var null|string|resource|\Psr\Http\Message\StreamInterface
     */
    protected $xmlFile = null;

    /**
     * @return null|string|resource|\Psr\Http\Message\StreamInterface
     */
    public function getXmlFile()
    {
        return $this->xmlFile;
    }

    /**
     * @param string|resource|\Psr\Http\Message\StreamInterface $value
     *
     * @return $this
     */
    public function setXmlFile($value)
    {
        $this->xmlFile = $value;

        return $this;
    }
    // endregion

    // endregion

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

        /** @var \Sweetchuck\Robo\PHPUnit\PHPUnitXmlParser $phpunitXmlParser */
        $phpunitXmlParser = new $this->phpunitXmlParserClass();
        $this->assets = $phpunitXmlParser->parse($xmlString, $this->getWorkingDirectory());

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
        if ($xmlFile instanceof StreamInterface) {
            return $this->getXmlStringFromStream($xmlFile);
        }

        if (is_resource($xmlFile)) {
            return $this->getXmlStringFromResource($xmlFile);
        }

        $xmlFile = (string) $xmlFile;

        return $this->isXmlString($xmlFile) ?
            $xmlFile
            : $this->getXmlStringFromFile($xmlFile);
    }

    protected function getXmlStringFromStream(StreamInterface $stream): string
    {
        $currentPosition = $stream->tell();
        $stream->rewind();
        $content = $stream->getContents();
        $stream->seek($currentPosition);

        return $content;
    }

    /**
     * @param resource $resource
     */
    protected function getXmlStringFromResource($resource): ?string
    {
        if (get_resource_type($resource) !== 'stream') {
            return null;
        }

        $currentPosition = ftell($resource);
        rewind($resource);
        $content = stream_get_contents($resource);
        fseek($resource, $currentPosition);

        return $content !== false ? $content : null;
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

    protected function isXmlString(string $content): bool
    {
        return mb_strpos($content, '<?xml') === 0 || mb_strpos($content, '<phpunit') === 0;
    }
}
