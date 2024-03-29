<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit;

interface OutputParserInterface
{

    /**
     * @return array<string, string>
     */
    public function getAssetNameMapping(): array;

    /**
     * @param array<string, string> $value
     */
    public function setAssetNameMapping(array $value): static;

    /**
     * @return array<string, mixed>
     */
    public function parse(int $exitCode, string $stdOutput, string $stdError): array;
}
