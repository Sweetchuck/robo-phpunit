<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\OutputParser;

class GroupsOutputParser extends ListOutputParser
{

    public function parse(
        int $exitCode,
        string $stdOutput,
        string $stdError,
    ): array {
        $return = parent::parse($exitCode, $stdOutput, $stdError);
        $assetNameItems = $this->getExternalAssetName('items');
        if (isset($return['assets'][$assetNameItems])) {
            $return['assets'][$assetNameItems] = array_map(
                function (string $line): string {
                    return (string) preg_replace('/ \(\d+ tests?\)$/', '', $line);
                },
                $return['assets'][$assetNameItems],
            );
        }

        return $return;
    }
}
