<?php

namespace Sweetchuck\Robo\PHPUnit\Task;

use Sweetchuck\Robo\PHPUnit\OutputParser\ListOutputParser;

class ListSuitesTask extends BaseCliTask
{
    /**
     * {@inheritdoc}
     */
    protected $taskName = 'PHPUnit - List suites';

    /**
     * {@inheritdoc}
     */
    protected $outputParserClass = ListOutputParser::class;

    /**
     * {@inheritdoc}
     */
    protected $outputParserAssetNameMapping = [
        'items' => 'phpunit.suitNames',
    ];

    /**
     * {@inheritdoc}
     */
    protected function initOptions()
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

    protected function runInitAssets()
    {
        parent::runInitAssets();
        $this->assets['phpunit.suitNames'] = null;

        return $this;
    }
}
