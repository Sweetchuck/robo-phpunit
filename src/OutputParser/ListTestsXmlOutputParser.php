<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\OutputParser;

class ListTestsXmlOutputParser extends ParserBase
{
    protected array $assetNameMapping = [
        'xml' => 'xml',
    ];

    public function parse(int $exitCode, string $stdOutput, string $stdError): array
    {
        if ($exitCode !== 0) {
            return [];
        }

        $startPos = strpos($stdOutput, '<?xml');
        $endMarker = "\n</tests>";
        $endMarkerLength = strlen($endMarker);
        $endPos = strpos($stdOutput, $endMarker);

        if ($startPos === false || $endPos === false) {
            return [
                'exitCode' => 2,
                'assets' => [],
            ];
        }

        $assetNameItems = $this->getExternalAssetName('xml');

        return [
            'assets' => [
                $assetNameItems => substr(
                    $stdOutput,
                    $startPos,
                    $endPos - $startPos + $endMarkerLength,
                ),
            ],
        ];
    }
}
