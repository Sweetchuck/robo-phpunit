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
}
