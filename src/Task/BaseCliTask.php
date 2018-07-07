<?php

namespace Sweetchuck\Robo\PHPUnit\Task;

use Robo\Common\OutputAwareTrait;
use Robo\Contract\CommandInterface;
use Robo\Contract\OutputAwareInterface;
use Sweetchuck\Robo\PHPUnit\Utils;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Process\Process;

/**
 * @method string getPhpExecutable()
 * @method $this  setPhpExecutable(string $path)
 * @method string getPhpunitExecutable()
 * @method $this  setPhpunitExecutable(string $path)
 * @method string getConfiguration()
 * @method $this  setConfiguration(string $path)
 * @method bool   getNoConfiguration()
 * @method $this  setNoConfiguration(bool $useTheDefaultConfigXml)
 * @method array  getArguments()
 * @method $this  setArguments(array $arguments)
 */
abstract class BaseCliTask extends BaseTask implements CommandInterface, OutputAwareInterface
{
    use OutputAwareTrait;

    /**
     * @var array
     */
    protected $cmdPattern = [];

    /**
     * @var array
     */
    protected $cmdArgs = [];

    /**
     * @var string
     */
    protected $command = '';

    /**
     * @var array
     */
    protected $optionGroupWeights = [
        'coverage' => 100,
        'coverageOther' => 200,
        'logging' => 300,
        'testExecution' => 400,
        'configuration' => 500,
        'testSelection' => 600,
    ];

    /**
     * @var null|\Closure
     */
    protected $processRunCallbackWrapper = null;

    protected function initOptions()
    {
        parent::initOptions();
        $this->options += [
            'phpExecutable' => [
                'type' => 'other',
                'value' => 'phpdbg -qrr',
            ],
            'phpunitExecutable' => [
                'type' => 'other',
                // @todo Autodetect the "bin-dir" of Composer.
                'value' => 'vendor/bin/phpunit',
            ],
            'configuration' => [
                'type' => 'option:value',
                'value' => '',
                'weight' => $this->optionGroupWeights['configuration'],
            ],
            'noConfiguration' => [
                'type' => 'option:flag',
                'value' => false,
                'weight' => $this->optionGroupWeights['configuration'],
            ],
            'arguments' => [
                'type' => 'argument:multi',
                'weight' => 999,
                'value' => [],
            ],
        ];

        return $this;
    }

    /**
     * @return $this
     */
    public function addArgument(string $argument)
    {
        $this->options['arguments']['value'][$argument] = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function removeArgument(string $argument)
    {
        unset($this->options['arguments']['value'][$argument]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCommand()
    {
        return $this
            ->getCommandInit()
            ->getCommandChangeDirectory()
            ->getCommandPrefix()
            ->getCommandEnvironmentVariables()
            ->getCommandPhpExecutable()
            ->getCommandPhpunitExecutable()
            ->getCommandPhpunitOptions()
            ->getCommandPhpunitArguments()
            ->getCommandBuild();
    }

    /**
     * @return $this
     */
    protected function getCommandInit()
    {
        $this->cmdPattern = [];
        $this->cmdArgs = [];

        return $this;
    }

    /**
     * @return $this
     */
    protected function getCommandChangeDirectory()
    {
        if ($this->options['workingDirectory']['value']) {
            $this->cmdPattern[] = 'cd %s &&';
            $this->cmdArgs[] = escapeshellarg($this->options['workingDirectory']['value']);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function getCommandPrefix()
    {
        return $this;
    }

    protected function getCommandEnvironmentVariables()
    {
        return $this;
    }

    /**
     * @return $this
     */
    protected function getCommandPhpExecutable()
    {
        if ($this->options['phpExecutable']['value']) {
            $this->cmdPattern[] = '%s';
            // @todo Unescaped user input.
            // But will work with "/foo bar/phpdbg -qrr" also.
            $this->cmdArgs[] = $this->options['phpExecutable']['value'];
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function getCommandPhpunitExecutable()
    {
        $this->cmdPattern[] = '%s';
        $this->cmdArgs[] = escapeshellcmd($this->options['phpunitExecutable']['value']);

        return $this;
    }

    /**
     * @return $this
     */
    protected function getCommandPhpunitOptions()
    {
        //codecept_debug(array_keys($this->options));
        foreach ($this->options as $optionName => $option) {
            $optionCliName = $option['cliName'];
            switch ($option['type']) {
                case 'option:flag':
                    if ($option['value']) {
                        $this->cmdPattern[] = "--$optionCliName";
                    }
                    break;

                case 'option:tri-state':
                    if ($option['value'] !== null) {
                        $this->cmdPattern[] = $option['value'] ? "--$optionCliName" : "--no-$optionCliName";
                    }
                    break;

                case 'option:value':
                    // @todo Handle empty strings or "0".
                    if ($option['value']) {
                        $this->cmdPattern[] = "--$optionCliName=%s";
                        $this->cmdArgs[] = escapeshellarg($option['value']);
                    }
                    break;

                case 'option:value:list':
                    $values = Utils::filterEnabled($option['value']);
                    if ($values) {
                        $separator = $option['separator'] ?? ',';
                        $this->cmdPattern[] = "--$optionCliName=%s";
                        $this->cmdArgs[] = escapeshellarg(implode($separator, $values));
                    }
                    break;

                case 'option:value:multi':
                    $values = Utils::filterEnabled($option['value']);
                    if ($values) {
                        $this->cmdPattern[] = str_repeat("--$optionCliName=%s", count($values));
                        foreach ($values as $value) {
                            $this->cmdArgs[] = escapeshellarg($value);
                        }
                    }
                    break;

                case 'argument:multi':
                    foreach (Utils::filterEnabled($option['value']) as $value) {
                        $this->cmdPattern[] = '%s';
                        $this->cmdArgs[] = escapeshellarg($value);
                    }
                    break;
            }
        }

        return $this;
    }

    protected function getCommandPhpunitArguments()
    {
        return $this;
    }

    protected function getCommandBuild(): string
    {
        return vsprintf(implode(' ', $this->cmdPattern), $this->cmdArgs);
    }

    /**
     * {@inheritdoc}
     */
    protected function runInit()
    {
        $this->runInitProcessRunCallbackWrapper();
        $this->command = $this->getCommand();

        return $this;
    }

    protected function runInitProcessRunCallbackWrapper()
    {
        $this->processRunCallbackWrapper = function (string $type, string $data): void {
            $this->processRunCallback($type, $data);
        };

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function runHeader()
    {
        $this->printTaskInfo(
            'runs "<info>{command}</info>"',
            [
                'command' => $this->command,
            ]
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function runDoIt()
    {
        $process = $this
            ->getProcessHelper()
            ->run($this->output(), $this->command, null, $this->processRunCallbackWrapper);

        $this->processExitCode = $process->getExitCode();
        $this->processStdOutput = $process->getOutput();
        $this->processStdError = $process->getErrorOutput();

        return $this;
    }

    protected function processRunCallback(string $type, string $data): void
    {
        switch ($type) {
            case Process::OUT:
                $this->output()->write($data);
                break;

            case Process::ERR:
                $this->printTaskError($data);
                break;
        }
    }

    protected function getProcessHelper(): ProcessHelper
    {
        return $this
            ->getContainer()
            ->get('application')
            ->getHelperSet()
            ->get('process');
    }
}
