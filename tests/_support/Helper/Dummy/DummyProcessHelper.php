<?php

namespace Sweetchuck\Robo\PHPUnit\Test\Helper\Dummy;

use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyProcess;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class DummyProcessHelper extends ProcessHelper
{
    public function run(
        OutputInterface $output,
        $cmd,
        $error = null,
        callable $callback = null,
        $verbosity = OutputInterface::VERBOSITY_VERY_VERBOSE
    ) {
        if ($cmd instanceof Process) {
            $cmd = $cmd->getCommandLine();
        }

        if (is_string($cmd)) {
            return DummyProcess::fromShellCommandline($cmd);
        }

        return new DummyProcess($cmd);
    }
}
