<?php

namespace Sweetchuck\Robo\PHPUnit\OutputParser;

class PhpdbgOutputParser extends ParserBase
{
    public function parse(int $exitCode, string $stdOutput, string $stdError): array
    {
        $errorMessagePatterns = [
            '@^(\x1b\[1;31m){0,1}' . preg_quote('[Could not open file ', '@') . '@',
            '@^(\x1b\[1;31m){0,1}' . preg_quote('[Failed to compile ', '@') . '@',
        ];

        $numOfKnownErrorMessages = count($errorMessagePatterns);

        $stdOutputLines = explode(PHP_EOL, $stdOutput, $numOfKnownErrorMessages + 1);
        unset($stdOutputLines[$numOfKnownErrorMessages]);

        $return = [
            'exitCode' => 0,
            'errorMessages' => [],
        ];
        foreach ($stdOutputLines as $stdOutputLine) {
            foreach ($errorMessagePatterns as $exitCode => $errorMessagePattern) {
                if (preg_match($errorMessagePattern, $stdOutputLine)) {
                    if (!$return['exitCode']) {
                        $return['exitCode'] = 120 + $exitCode;
                    }

                    $return['errorMessages'][] = $stdOutputLine;
                }
            }
        }

        return $return;
    }
}
