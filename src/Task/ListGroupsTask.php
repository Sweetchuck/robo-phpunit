<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Task;

use Sweetchuck\Robo\PHPUnit\OutputParser\ListOutputParser;

class ListGroupsTask extends BaseCliTask
{
    /**
     * {@inheritdoc}
     */
    protected $taskName = 'PHPUnit - List groups';

    /**
     * {@inheritdoc}
     */
    protected $outputParserClass = ListOutputParser::class;

    /**
     * {@inheritdoc}
     */
    protected $outputParserAssetNameMapping = [
        'items' => 'phpunit.groupNames',
    ];

    /**
     * {@inheritdoc}
     */
    protected function initOptions()
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

    protected function runInitAssets()
    {
        parent::runInitAssets();
        $this->assets['phpunit.groupNames'] = null;

        return $this;
    }
}
