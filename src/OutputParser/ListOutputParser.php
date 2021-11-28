<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\OutputParser;

class ListOutputParser extends ParserBase
{
    protected array $assetNameMapping = [
        'items' => 'items',
    ];

    public function parse(int $exitCode, string $stdOutput, string $stdError): array
    {
        if ($exitCode !== 0) {
            return [];
        }

        $assetNameItems = $this->getExternalAssetName('items');
        $return = [
            'assets' => [
                $assetNameItems => [],
            ],
        ];

        $pattern = '/^ - (?P<items>.+?)$/smiu';
        $matches = [];
        if (preg_match_all($pattern, $stdOutput, $matches)) {
            $return['assets'][$assetNameItems] = $matches['items'];
        }

        return $return;
    }
}
