<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Task;

use Sweetchuck\Robo\PHPUnit\OutputParser\ListOutputParser;

class ListTestsTask extends BaseCliTask
{
    /**
     * {@inheritdoc}
     */
    protected $taskName = 'PHPUnit - List tests';

    /**
     * {@inheritdoc}
     */
    protected $outputParserClass = ListOutputParser::class;

    /**
     * {@inheritdoc}
     */
    protected $outputParserAssetNameMapping = [
        'items' => 'phpunit.testMethods',
    ];

    /**
     * {@inheritdoc}
     */
    protected function initOptions()
    {
        parent::initOptions();
        $this->options += [
            'listTests' => [
                'type' => 'option:flag',
                'value' => true,
            ],
        ];

        return $this;
    }

    protected function runInitAssets()
    {
        parent::runInitAssets();
        $this->assets['phpunit.testMethods'] = null;

        return $this;
    }
}
