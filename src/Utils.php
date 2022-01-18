<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit;

use Webmozart\PathUtil\Path;

class Utils
{
    public static function filterEnabled(array $items): array
    {
        return (gettype(reset($items)) === 'boolean') ?
            array_keys($items, true, true)
            : $items;
    }

    public static function isPhpDbg(string $phpExecutable): bool
    {
        return mb_strpos(Path::getFilename($phpExecutable), 'phpdbg') !== false;
    }

    public static function getXmlString(
        $xmlFile,
        string $rootTagName = '',
        string $workingDirectory = ''
    ): ?string {
        if (is_resource($xmlFile)) {
            return static::getXmlStringFromResource($xmlFile);
        }

        $xmlFile = (string) $xmlFile;

        return Utils::isXmlString($xmlFile, $rootTagName) ?
            $xmlFile
            : static::getXmlStringFromFile($xmlFile, $workingDirectory);
    }

    /**
     * @param resource $resource
     */
    public static function getXmlStringFromResource($resource): ?string
    {
        if (get_resource_type($resource) !== 'stream') {
            return null;
        }

        $content = stream_get_contents($resource);

        return $content !== false ? $content : null;
    }

    public static function getXmlStringFromFile(string $fileName, string $workingDirectory = ''): ?string
    {
        if ($fileName === '') {
            return null;
        }

        if (Path::isRelative($fileName) && $workingDirectory !== '') {
            $fileName = Path::join($workingDirectory, $fileName);
        }

        $content = file_get_contents($fileName);

        return $content !== false ? $content : null;
    }

    /**
     * @todo This isn't bulletproof, it still can be a file name.
     */
    public static function isXmlString(string $content, string $rootTagName = ''): bool
    {
        if (mb_strpos($content, '<?xml') === 0) {
            return true;
        }

        return $rootTagName !== '' && mb_strpos($content, "<$rootTagName") === 0;
    }

    public static function delimit(string $text, string $delimiter): string
    {
        $str = mb_ereg_replace('\B([A-Z])', '-\1', trim($text), 'msr');
        $str = mb_strtolower($str);

        return mb_ereg_replace('[-_\s]+', $delimiter, $str);
    }
}
