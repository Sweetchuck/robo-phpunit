# Robo task to run PHPUnit related tasks

[![CircleCI](https://circleci.com/gh/Sweetchuck/robo-phpunit/tree/2.x.svg?style=svg)](https://circleci.com/gh/Sweetchuck/robo-phpunit/?branch=2.x)
[![codecov](https://codecov.io/gh/Sweetchuck/robo-phpunit/branch/2.x/graph/badge.svg?token=HSF16OGPyr)](https://app.codecov.io/gh/Sweetchuck/robo-phpunit/branch/2.x)


## Install

`composer require --dev sweetchuck/robo-phpunit`


## Task - taskPHPUnitListGroupsTask

```php
<?php

class RoboFile extends \Robo\Tasks
{
    use \Sweetchuck\Robo\PHPUnit\PHPUnitTaskLoader;
    
    /**
     * @command phpunit:list-groups
     */
    public function cmdListGroups()
    {
        return $this
            ->collectionBuilder()
            ->addTask($this->taskPHPUnitListGroups())
            ->addCode(function (\Robo\State\Data $data): int {
                $output = $this->output();
                foreach ($data['phpunit.groupNames'] as $groupName) {
                    $output->writeln($groupName);
                }

                return 0;
            });
    }
}
```

Run `vendor/bin/robo phpunit:list-groups` \
Example output:
> <pre>foo
> bar</pre>


## Task - taskPHPUnitListSuitesTask

```php
<?php

class RoboFile extends \Robo\Tasks
{
    use \Sweetchuck\Robo\PHPUnit\PHPUnitTaskLoader;
}
```

Run `vendor/bin/robo phpunit:` \
Example output:
> <pre></pre>


## Task - taskPHPUnitListTestsTask

```php
<?php

class RoboFile extends \Robo\Tasks
{
    use \Sweetchuck\Robo\PHPUnit\PHPUnitTaskLoader;
}
```

Run `vendor/bin/robo phpunit:` \
Example output:
> <pre></pre>


## Task - taskPHPUnitParseXml

```php
<?php

class RoboFile extends \Robo\Tasks
{
    use \Sweetchuck\Robo\PHPUnit\PHPUnitTaskLoader;
}
```

Run `vendor/bin/robo phpunit:`  
Example output:
> <pre></pre>


## Task - taskPHPUnitRun

```php
<?php

class RoboFile extends \Robo\Tasks
{
    use \Sweetchuck\Robo\PHPUnit\PHPUnitTaskLoader;
}
```

Run `vendor/bin/robo phpunit:`  
Example output:
> <pre></pre>
