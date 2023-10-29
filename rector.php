<?php

declare(strict_types=1);
/**
 * © 2023-2023 by the orcano team (https://github.com/maxs94/orcano)
 */

use Rector\CodingStyle\Rector\ClassConst\RemoveFinalFromConstRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Php81\Rector\ClassConst\FinalizePublicClassConstantRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $rectorConfig->importShortClasses();

    $rectorConfig->rule(RemoveFinalFromConstRector::class);
    
    $rectorConfig->sets([
        SetList::CODE_QUALITY,
        LevelSetList::UP_TO_PHP_82,
        SetList::DEAD_CODE,
        SetList::TYPE_DECLARATION,
        SetList::EARLY_RETURN,
    ]);

    $rectorConfig->skip([
        FinalizePublicClassConstantRector::class,
    ]);
    
};
