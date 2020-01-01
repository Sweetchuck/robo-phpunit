<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\OutputParser;

use Sweetchuck\Robo\PHPUnit\OutputParserInterface;

abstract class ParserBase implements OutputParserInterface
{

    // region assetNameMapping
    /**
     * @var array
     */
    protected $assetNameMapping = [];

    public function getAssetNameMapping(): array
    {
        return $this->assetNameMapping;
    }

    /**
     * @return $this
     */
    public function setAssetNameMapping(array $value)
    {
        $this->assetNameMapping = $value;

        return $this;
    }
    // endregion

    protected function getExternalAssetName(string $internalAssetName): string
    {
        return $this->assetNameMapping[$internalAssetName];
    }

    /**
     * {@inheritdoc}
     */
    abstract public function parse(int $exitCode, string $stdOutput, string $stdError): array;
}
