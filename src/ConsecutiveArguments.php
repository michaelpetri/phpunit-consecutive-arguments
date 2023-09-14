<?php

declare(strict_types=1);

namespace MichaelPetri\PhpunitConsecutiveArguments;

use PHPUnit\Framework\Constraint\Callback;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsAnything;
use PHPUnit\Framework\Constraint\IsEqual;

final class ConsecutiveArguments
{
    private function __construct()
    {
    }

    /**
     * @param list<mixed> $firstConsecutiveArgument
     * @param list<list<mixed>> $additionalConsecutiveArguments
     *
     * @no-named-arguments
     *
     * @return iterable<Callback<mixed>>
     */
    public static function of(array $firstConsecutiveArgument, array ...$additionalConsecutiveArguments): iterable
    {
        $consecutiveArguments = [$firstConsecutiveArgument, ...$additionalConsecutiveArguments];

        $argumentCount = \count(\max($consecutiveArguments));

        /** @var array<positive-int|0, list<mixed>> $argumentsGroupedByPosition */
        $argumentsGroupedByPosition = [];

        foreach ($consecutiveArguments as $arguments) {
            /** @var list<mixed> $arguments */
            $arguments = \array_pad(
                $arguments,
                $argumentCount,
                new IsAnything()
            );

            /** @var mixed $argument */
            foreach ($arguments as $position => $argument) {
                $argumentsGroupedByPosition[$position] = $argumentsGroupedByPosition[$position] ?? [];
                $argumentsGroupedByPosition[$position][] = $argument;
            }
        }

        for ($argumentPosition = 0; $argumentPosition < $argumentCount; $argumentPosition++) {
            yield new Callback(
                static function (mixed $actualArgument) use (&$argumentsGroupedByPosition, $argumentPosition): bool {
                    /**
                     * @var array<positive-int|0, array<array-key, mixed>> $argumentsGroupedByPosition
                     * @var mixed $expectedArgument
                     */
                    $expectedArgument = \array_key_exists($argumentPosition, $argumentsGroupedByPosition)
                        ? \array_shift($argumentsGroupedByPosition[$argumentPosition])
                        : null;

                    if (!$expectedArgument instanceof Constraint) {
                        $expectedArgument = new IsEqual($expectedArgument);
                    }

                    return (bool) $expectedArgument->evaluate($actualArgument, '', true);
                }
            );
        }
    }
}
