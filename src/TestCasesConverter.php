<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Input XML comes from `phpunit --list-tests-xml='php://stdout'`.
 *
 * WARNING: Resolve file names from class names works only if the classes are
 * available from the same autoloader.
 */
class TestCasesConverter
{
    protected Filesystem $filesystem;

    public function __construct(?Filesystem $filesystem = null)
    {
        $this->filesystem = $filesystem ?: new Filesystem();
    }

    /**
     * Plain list of file names.
     */
    public function toFileNames(
        string $xmlString,
        string $fileNameRelativeTo = '',
        array $bootstrapFiles = []
    ): array {
        $this->requireFiles($bootstrapFiles);

        $xml = new \DOMDocument();
        $xml->loadXML($xmlString);
        $xpath = new \DOMXPath($xml);
        $fileNames = [];
        /** @var \DOMAttr $classNameAttribute */
        foreach ($xpath->query('/tests/testCaseClass/@name') as $classNameAttribute) {
            $fileNames[] = $this->classNameToFileName($classNameAttribute->value, $fileNameRelativeTo);
        }

        return array_unique($fileNames);
    }

    /**
     * Converts XML to an array.
     *
     * Every row contains information about a test method with data set.
     *
     * @param bool[] $bootstrapFiles
     *   Keys are column names.
     */
    public function toArray(
        string $xmlString,
        string $fileNameRelativeTo = '',
        array $bootstrapFiles = [],
        array $granularity = []
    ): array {
        $this->requireFiles($bootstrapFiles);

        $xml = new \DOMDocument();
        $xml->loadXML($xmlString);

        $columns = [
            'file' => null,
            'class' => null,
            'classRegexSafe' => null,
            'method' => null,
            'dataSet' => null,
            'dataSetRegexSafe' => null,
            'groups' => null,
        ];

        $granularity = array_flip(array_keys($granularity, true, true));

        $rows = [];
        /** @var \DOMElement $class */
        foreach ($xml->getElementsByTagName('testCaseClass') as $class) {
            $classFields = $this->parseTestCaseClassElement($class, $fileNameRelativeTo);
            /** @var \DOMElement $method */
            foreach ($class->getElementsByTagName('testCaseMethod') as $method) {
                $row = array_replace(
                    $columns,
                    $classFields,
                    $this->parseTestCaseMethodElement($method),
                );

                if (!$granularity) {
                    $rows[] = $row;

                    continue;
                }

                $id = implode('|', array_intersect_key($row, $granularity));
                $rows[$id] = $row;
            }
        }

        return $rows;
    }

    public function toCsv(
        string $xmlString,
        string $fileNameRelativeTo = '',
        array $bootstrapFiles = [],
        array $granularity = [],
        bool $withHeader = true,
        array $csvOptions = []
    ): string {
        $rows = $this->toArray($xmlString, $fileNameRelativeTo, $bootstrapFiles, $granularity);

        if (!$rows) {
            return '';
        }

        $handler = fopen('php://memory', 'r+');
        if ($withHeader) {
            $row = (array) reset($rows);
            fputcsv($handler, array_keys($row), ...$csvOptions);
        }

        foreach ($rows as $row) {
            fputcsv($handler, $row, ...$csvOptions);
        }

        rewind($handler);
        $csv = stream_get_contents($handler);
        fclose($handler);

        return $csv;
    }

    protected function parseTestCaseClassElement(\DOMElement $class, string $fileNameRelativeTo): array
    {
        $name = $class->getAttribute('name');

        return [
            'file' => $this->classNameToFileName($name, $fileNameRelativeTo),
            'class' => $name,
            'classRegexSafe' => preg_quote($class->getAttribute('name')),
        ];
    }

    protected function parseTestCaseMethodElement(\DOMElement $method): array
    {
        $dataSet = $method->getAttribute('dataSet');

        return [
            'method' => $method->getAttribute('name'),
            'dataSet' => $dataSet,
            'dataSetRegexSafe' => preg_quote($dataSet),
            'groups' => $method->getAttribute('groups'),
        ];
    }

    public function classNameToFileName(string $class, string $fileNameRelativeTo): string
    {
        // @todo Error handling.
        $classReflection = new \ReflectionClass($class);
        $fileName = $classReflection->getFileName();

        if ($fileNameRelativeTo === '') {
            return $fileName;
        }

        // @see https://github.com/symfony/symfony/pull/40051
        return rtrim(
            $this->filesystem->makePathRelative($fileName, $fileNameRelativeTo),
            '/\\',
        );
    }

    protected function requireFiles(iterable $files)
    {
        foreach ($files as $file) {
            require_once $file;
        }

        return $this;
    }
}
