<?php

declare(strict_types=1);

use MichaelPetri\PhpunitConsecutiveArguments\ConsecutiveArgumentsRectorRule;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/rector-test',
    ]);

    $rectorConfig->rule(ConsecutiveArgumentsRectorRule::class);
};
