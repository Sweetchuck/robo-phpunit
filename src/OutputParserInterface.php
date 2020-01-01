<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit;

interface OutputParserInterface
{
    public function getAssetNameMapping(): array;

    /**
     * @return $this
     */
    public function setAssetNameMapping(array $value);

    public function parse(int $exitCode, string $stdOutput, string $stdError): array;
}
