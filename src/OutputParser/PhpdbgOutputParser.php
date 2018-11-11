<?php

namespace Sweetchuck\Robo\PHPUnit\OutputParser;

class PhpdbgOutputParser extends ParserBase
{

    /**
     * @var int
     */
    protected $exitCodeOffset = 120;

    public function parse(int $exitCode, string $stdOutput, string $stdError): array
    {
        $errorMessagePatterns = $this->getErrorMessagePatterns();
        $numOfKnownErrorMessages = count($errorMessagePatterns);

        $stdOutputLines = explode(PHP_EOL, $stdOutput, $numOfKnownErrorMessages + 1);
        unset($stdOutputLines[$numOfKnownErrorMessages]);

        $return = [
            'exitCode' => 0,
            'errorMessages' => [],
        ];
        foreach ($stdOutputLines as $stdOutputLine) {
            $newExitCode = $this->getExitCode($stdOutputLine);
            if ($newExitCode) {
                $return['errorMessages'][] = $stdOutputLine;

                if (!$return['exitCode']) {
                    $return['exitCode'] = $newExitCode;
                }
            }
        }

        return $return;
    }

    protected function getExitCode(string $stdOutputLine): int
    {
        foreach ($this->getErrorMessagePatterns() as $exitCode => $errorMessagePattern) {
            if (preg_match($errorMessagePattern, $stdOutputLine)) {
                return $exitCode;
            }
        }

        return 0;
    }

    protected function getErrorMessagePatterns(): array
    {
        return [
            $this->exitCodeOffset + 1 => '/^(..\[1;31m){0,1}' . preg_quote('[Could not open file ') . '/u',
            $this->exitCodeOffset + 2 => '/^(..\[1;31m){0,1}' . preg_quote('[Failed to compile ') . '/u',
        ];
    }
}
