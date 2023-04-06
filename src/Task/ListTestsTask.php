<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Task;

use Sweetchuck\Robo\PHPUnit\OutputParser\ListOutputParser;

/**
 * @method array  getTestsuite()
 * @method $this  setTestsuite(array $names)
 * @method array  getGroup()
 * @method $this  setGroup(array $names)
 * @method array  getExcludeGroup()
 * @method $this  setExcludeGroup(array $names)
 * @method string getCovers()
 * @method $this  setCovers(string $name)
 * @method string getUses()
 * @method $this  setUses(string $name)
 * @method string getFilter()
 * @method $this  setFilter(string $name)
 * @method string getTestSuffix()
 * @method $this  setTestSuffix(array $names)
 */
class ListTestsTask extends BaseCliTask
{
    protected string $taskName = 'PHPUnit - List tests';

    protected ?string $outputParserClass = ListOutputParser::class;

    protected array $outputParserAssetNameMapping = [
        'items' => 'phpunit.testMethods',
    ];

    protected function initOptions(): static
    {
        parent::initOptions();
        $this->options += [
            'listTests' => [
                'type' => 'option:flag',
                'value' => true,
            ],
        ];
        $this->options += $this->initOptionsTestSelection();

        return $this;
    }

    protected function runInitAssets(): static
    {
        parent::runInitAssets();
        $this->assets['phpunit.testMethods'] = null;

        return $this;
    }
}
