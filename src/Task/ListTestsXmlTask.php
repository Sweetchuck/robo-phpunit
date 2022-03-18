<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\PHPUnit\Task;

use Sweetchuck\Robo\PHPUnit\OutputParser\ListTestsXmlOutputParser;

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
class ListTestsXmlTask extends BaseCliTask
{
    protected string $taskName = 'PHPUnit - List tests XML';

    protected ?string $outputParserClass = ListTestsXmlOutputParser::class;

    protected array $outputParserAssetNameMapping = [
        'xml' => 'phpunit.tests.xml',
    ];

    /**
     * {@inheritdoc}
     */
    protected function initOptions()
    {
        parent::initOptions();
        $this->options += [
            'listTestsXml' => [
                'type' => 'option:value',
                'value' => 'php://stdout',
            ],
        ];
        $this->options += $this->initOptionsTestSelection();

        return $this;
    }

    protected function runInitAssets()
    {
        parent::runInitAssets();
        $this->assets['phpunit.tests.xml'] = null;

        return $this;
    }
}
