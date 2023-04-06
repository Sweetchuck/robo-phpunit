<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Task;

use Sweetchuck\Robo\PHPUnit\OutputParser\ListOutputParser;

class ListSuitesTask extends BaseCliTask
{
    protected string $taskName = 'PHPUnit - List suites';

    protected ?string $outputParserClass = ListOutputParser::class;

    protected array $outputParserAssetNameMapping = [
        'items' => 'phpunit.suitNames',
    ];

    protected function initOptions(): static
    {
        parent::initOptions();
        $this->options += [
            'listSuites' => [
                'type' => 'option:flag',
                'value' => true,
            ],
        ];

        return $this;
    }

    protected function runInitAssets(): static
    {
        parent::runInitAssets();
        $this->assets['phpunit.suitNames'] = null;

        return $this;
    }
}
