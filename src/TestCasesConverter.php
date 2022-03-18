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
     *
     * @param iterable<string> $bootstrapFiles
     *
     * @return array<int, string>
     */
    public function toFileNames(
        string $xmlString,
        string $fileNameRelativeTo = '',
        iterable $bootstrapFiles = []
    ): array {
        $this->requireFiles($bootstrapFiles);

        $xml = new \DOMDocument();
        $xml->loadXML($xmlString);
        $xpath = new \DOMXPath($xml);
        $fileNames = [];

        $list = $xpath->query('/tests/testCaseClass/@name');
        if ($list === false) {
            return $fileNames;
        }

        /** @var \DOMAttr $classNameAttribute */
        foreach ($list as $classNameAttribute) {
            /** @var class-string $className */
            $className = $classNameAttribute->value;
            $fileNames[] = $this->classNameToFileName($className, $fileNameRelativeTo);
        }

        return array_unique($fileNames);
    }

    /**
     * Converts XML to an array.
     *
     * Every row contains information about a test method with data set.
     *
     * @param iterable<string> $bootstrapFiles
     *   Keys are column names.
     * @param array<string, bool> $granularity
     *
     * @return array<int|string, test-case-row-array>
     */
    public function toArray(
        string $xmlString,
        string $fileNameRelativeTo = '',
        iterable $bootstrapFiles = [],
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
                /** @phpstan-var test-case-row-array $row */
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

    /**
     * @param array<int, string> $bootstrapFiles
     * @param array<string, bool> $granularity
     * @param csv-options-array $csvOptions
     *
     * @return string
     */
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
        if ($handler === false) {
            return '';
        }

        if ($withHeader) {
            $row = (array) reset($rows);
            fputcsv($handler, array_keys($row), ...$csvOptions);
        }

        foreach ($rows as $row) {
            fputcsv($handler, $row, ...$csvOptions);
        }

        rewind($handler);
        $csv = (string) stream_get_contents($handler);
        fclose($handler);

        return $csv;
    }

    /**
     * @return test-case-class-array
     */
    protected function parseTestCaseClassElement(\DOMElement $class, string $fileNameRelativeTo): array
    {
        /** @var class-string $name */
        $name = $class->getAttribute('name');

        return [
            'file' => $this->classNameToFileName($name, $fileNameRelativeTo),
            'class' => $name,
            'classRegexSafe' => preg_quote($class->getAttribute('name')),
        ];
    }

    /**
     * @return test-case-method-array
     */
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

    /**
     * @param class-string $class
     * @param string $fileNameRelativeTo
     *
     * @return string
     * @throws \ReflectionException
     */
    public function classNameToFileName(string $class, string $fileNameRelativeTo): string
    {
        // @todo Error handling.
        $classReflection = new \ReflectionClass($class);
        $fileName = (string) $classReflection->getFileName();

        if ($fileNameRelativeTo === '') {
            return $fileName;
        }

        // @see https://github.com/symfony/symfony/pull/40051
        return rtrim(
            $this->filesystem->makePathRelative($fileName, $fileNameRelativeTo),
            '/\\',
        );
    }

    /**
     * @param iterable<string> $files
     *
     * @return $this
     */
    protected function requireFiles(iterable $files)
    {
        foreach ($files as $file) {
            require_once $file;
        }

        return $this;
    }
}
