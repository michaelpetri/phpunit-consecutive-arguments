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
     * @return iterable<array-key, Callback<mixed>>
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
                $argumentsGroupedByPosition[$position] ??= [];
                $argumentsGroupedByPosition[$position][] = $argument;
            }
        }

        for ($argumentPosition = 0; $argumentPosition < $argumentCount; $argumentPosition++) {
            yield new Callback(
                static function (mixed $actualArgument) use (&$argumentsGroupedByPosition, $argumentPosition): bool {
                    /**
                     * Get expected argument for current position, if list for position is empty it means we're
                     * evaluating additional executions which are not defined in test case, because of this we're going
                     * to accept anything.
                     *
                     * @var mixed $expectedArgument
                     */
                    $expectedArgument = \array_key_exists($argumentPosition, $argumentsGroupedByPosition)
                    && [] !== $argumentsGroupedByPosition[$argumentPosition]
                        ? \array_shift($argumentsGroupedByPosition[$argumentPosition])
                        : new IsAnything();

                    if (!$expectedArgument instanceof Constraint) {
                        $expectedArgument = new IsEqual($expectedArgument);
                    }

                    return (bool) $expectedArgument->evaluate($actualArgument, '', true);
                }
            );
        }
    }
}
