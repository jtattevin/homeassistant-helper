<?php

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
                   ->withAttributesSets(symfony: true)
                   ->withSymfonyContainerXml(__DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.xml')
                   ->withPaths([
                       __DIR__ . "/src",
                   ])
                   ->withPreparedSets(
                       deadCode: true,
                       codeQuality: true,
                       earlyReturn: true,
                   )
                   ->withSets([
                       LevelSetList::UP_TO_PHP_82,
                       SymfonySetList::SYMFONY_71,
                   ])
;
