<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\OutputParser;

use Sweetchuck\Robo\PHPUnit\OutputParserInterface;

abstract class ParserBase implements OutputParserInterface
{

    /**
     * @var array<string, string>
     */
    protected array $assetNameMapping = [];

    public function getAssetNameMapping(): array
    {
        return $this->assetNameMapping;
    }

    /**
     * {@inheritdoc}
     */
    public function setAssetNameMapping(array $value)
    {
        $this->assetNameMapping = $value;

        return $this;
    }

    protected function getExternalAssetName(string $internalAssetName): string
    {
        return $this->assetNameMapping[$internalAssetName];
    }

    abstract public function parse(int $exitCode, string $stdOutput, string $stdError): array;
}
