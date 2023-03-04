<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Task;

use Consolidation\AnnotatedCommand\Output\OutputAwareInterface;
use Robo\Common\OutputAwareTrait;
use Robo\Contract\CommandInterface;
use Sweetchuck\CliCmdBuilder\CommandBuilder;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Process\Process;

/**
 * @method bool    getHideStdOutput()
 * @method $this   setHideStdOutput(bool $hide)
 * @method null|int getProcessTimeout()
 * @method $this    setProcessTimeout(null|int $timeout)
 * @method string|\Sweetchuck\CliCmdBuilder\CommandBuilderInterface getPhpExecutable()
 * @method $this  setPhpExecutable(string|\Sweetchuck\CliCmdBuilder\CommandBuilderInterface $path)
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

    protected string $command = '';

    protected ?CommandBuilder $cmdBuilder = null;

    /**
     * @var array<string, int>
     */
    protected array $optionGroupWeights = [
        'coverage' => 100,
        'coverageOther' => 200,
        'logging' => 300,
        'testExecution' => 400,
        'configuration' => 500,
        'testSelection' => 600,
    ];

    protected ?\Closure $processRunCallbackWrapper = null;

    protected function initOptions()
    {
        parent::initOptions();
        $this->options += [
            'hideStdOutput' => [
                'type' => 'other',
                'value' => true,
            ],
            'processTimeout' => [
                'type' => 'other',
                'value' => 60,
            ],
            'envVars' => [
                'type' => 'other',
                'value' => [],
            ],
            'phpExecutable' => [
                'type' => 'executable',
                'value' => 'php',
            ],
            'phpunitExecutable' => [
                'type' => 'other',
                // @todo Autodetect the "bin-dir" of Composer.
                'value' => 'vendor/bin/phpunit',
            ],
            'configuration' => [
                'type' => 'option:value',
                'value' => null,
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
     * @return array<string, mixed>
     */
    protected function initOptionsTestSelection()
    {
        return [
            'testsuite' => [
                'type' => 'option:value:list',
                'value' => [],
            ],
            'group' => [
                'type' => 'option:value:list',
                'value' => [],
            ],
            'excludeGroup' => [
                'type' => 'option:value:list',
                'value' => [],
            ],
            'covers' => [
                'type' => 'option:value',
                'value' => null,
            ],
            'uses' => [
                'type' => 'option:value',
                'value' => null,
            ],
            'filter' => [
                'type' => 'option:value',
                'value' => null,
            ],
            'testSuffix' => [
                'type' => 'option:value:list',
                'value' => [],
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function getEnvVars(): array
    {
        return $this->options['envVars']['value'];
    }

    /**
     * @param array<string, string> $envVars
     *
     * @return $this
     */
    public function setEnvVars(array $envVars)
    {
        $this->options['envVars']['value'] = $envVars;

        return $this;
    }

    /**
     * @return $this
     */
    public function addEnvVar(string $name, string $value)
    {
        $this->options['envVars']['value'][$name] = $value;

        return $this;
    }

    /**
     * @param array<string, string> $envVars
     *
     * @return $this
     */
    public function addEnvVars(array $envVars)
    {
        foreach ($envVars as $name => $value) {
            $this->addEnvVar($name, $value);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function removeEnvVar(string $name)
    {
        unset($this->options['envVars']['value'][$name]);

        return $this;
    }

    /**
     * @param array<string> $names
     *
     * @return $this
     */
    public function removeEnvVars(array $names)
    {
        foreach ($names as $name) {
            $this->removeEnvVar($name);
        }

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
        $this->cmdBuilder = new CommandBuilder();

        return $this;
    }

    /**
     * @return $this
     */
    protected function getCommandEnvironmentVariables()
    {
        foreach ($this->options['envVars']['value'] as $name => $value) {
            $this->cmdBuilder->addEnvVar($name, $value);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function getCommandPhpExecutable()
    {
        if ($this->options['phpExecutable']['value']) {
            $this->cmdBuilder->setExecutable($this->options['phpExecutable']['value']);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function getCommandPhpunitExecutable()
    {
        if ($this->options['phpExecutable']['value']) {
            $this->cmdBuilder->addArgument($this->options['phpunitExecutable']['value']);

            return $this;
        }

        $this->cmdBuilder->setExecutable($this->options['phpunitExecutable']['value']);

        return $this;
    }

    /**
     * @return $this
     */
    protected function getCommandPhpunitOptions()
    {
        foreach ($this->options as $optionName => $option) {
            if (mb_strpos($option['type'], 'option:') !== 0) {
                continue;
            }

            $this->cmdBuilder->addOption($option['cliName'], $option['value'] ?? null, $option['type']);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function getCommandPhpunitArguments()
    {
        foreach (array_keys($this->options['arguments']['value'], true, true) as $argument) {
            $this->cmdBuilder->addArgument($argument, 'argument:single:unsafe');
        }

        return $this;
    }

    protected function getCommandBuild(): string
    {
        $chdir = '';
        if ($this->options['workingDirectory']['value']) {
            $chdir = sprintf(
                'cd %s && ',
                escapeshellarg($this->options['workingDirectory']['value'])
            );
        }

        return $chdir . (string) $this->cmdBuilder;
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

    /**
     * @return $this
     */
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
        $processInner = Process::fromShellCommandline($this->command);
        $processInner->setTimeout($this->options['processTimeout']['value']);

        $process = $this
            ->getProcessHelper()
            ->run($this->output(), $processInner, null, $this->processRunCallbackWrapper);

        $this->processExitCode = $process->getExitCode();
        $this->processStdOutput = $process->getOutput();
        $this->processStdError = $process->getErrorOutput();

        return $this;
    }

    protected function processRunCallback(string $type, string $data): void
    {
        switch ($type) {
            case Process::OUT:
                if (!$this->getHideStdOutput()) {
                    $this->output()->write($data);
                }
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
