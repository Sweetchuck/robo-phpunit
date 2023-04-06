<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Task;

use Sweetchuck\Robo\PHPUnit\OutputParser\ListOutputParser;

class ListGroupsTask extends BaseCliTask
{
    protected string $taskName = 'PHPUnit - List groups';

    protected ?string $outputParserClass = ListOutputParser::class;

    protected array $outputParserAssetNameMapping = [
        'items' => 'phpunit.groupNames',
    ];

    protected function initOptions(): static
    {
        parent::initOptions();
        $this->options += [
            'listGroups' => [
                'type' => 'option:flag',
                'value' => true,
            ],
        ];

        return $this;
    }

    protected function runInitAssets(): static
    {
        parent::runInitAssets();
        $this->assets['phpunit.groupNames'] = null;

        return $this;
    }
}
