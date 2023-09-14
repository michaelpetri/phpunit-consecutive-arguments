<?php

declare(strict_types=1);

namespace Tests\MichaelPetri\PhpunitConsecutiveArguments;

use MichaelPetri\PhpunitConsecutiveArguments\ConsecutiveArguments;
use PHPUnit\Framework\Constraint\IsAnything;
use PHPUnit\Framework\TestCase;
use Tests\MichaelPetri\PhpunitConsecutiveArguments\Stub\MockableConsecutiveArgumentInterface;

/** @covers \MichaelPetri\PhpunitConsecutiveArguments\ConsecutiveArguments */
final class ConsecutiveArgumentsTest extends TestCase
{
    private MockableConsecutiveArgumentInterface $mock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = $this->createMock(MockableConsecutiveArgumentInterface::class);
    }

    public function testOnMethodWithSingleArgument(): void
    {
        $this->mock
            ->expects(self::exactly(3))
            ->method('single')
            ->with(
                ...ConsecutiveArguments::of(
                    ['1'],
                    ['2'],
                    ['3'],
                )
            );

        $this->mock->single('1');
        $this->mock->single('2');
        $this->mock->single('3');
    }

    public function testOnMethodWithSingleOptionalArgument(): void
    {
        $this->mock
            ->expects(self::exactly(3))
            ->method('singleOptional')
            ->with(
                ...ConsecutiveArguments::of(
                    ['1'],
                    [],
                    ['3'],
                )
            );

        $this->mock->singleOptional('1');
        $this->mock->singleOptional();
        $this->mock->singleOptional('3');
    }

    public function testOnMethodWithSingleArgumentWithDefaultValue(): void
    {
        $this->mock
            ->expects(self::exactly(3))
            ->method('singleWithDefault')
            ->with(
                ...ConsecutiveArguments::of(
                    ['1'],
                    ['default'],
                    ['3'],
                )
            );

        $this->mock->singleWithDefault('1');
        $this->mock->singleWithDefault();
        $this->mock->singleWithDefault('3');
    }

    public function testOnMethodWithMultipleArguments(): void
    {
        $this->mock
            ->expects(self::exactly(3))
            ->method('multiple')
            ->with(
                ...ConsecutiveArguments::of(
                    ['1.1', '1.2'],
                    ['2.1', '2.2'],
                    ['3.1', '3.2'],
                )
            );

        $this->mock->multiple('1.1', '1.2');
        $this->mock->multiple('2.1', '2.2');
        $this->mock->multiple('3.1', '3.2');
    }

    public function testOnMethodWithMultipleArgumentsAndOptionalValue(): void
    {
        $this->mock
            ->expects(self::exactly(3))
            ->method('multipleWithOptional')
            ->with(
                ...ConsecutiveArguments::of(
                    ['1.1', '1.2'],
                    ['2.1'],
                    ['3.1', '3.2'],
                )
            );

        $this->mock->multipleWithOptional('1.1', '1.2');
        $this->mock->multipleWithOptional('2.1');
        $this->mock->multipleWithOptional('3.1', '3.2');
    }

    public function testOnMethodWithMultipleArgumentsAndDefaultValue(): void
    {
        $this->mock
            ->expects(self::exactly(3))
            ->method('multipleWithDefault')
            ->with(
                ...ConsecutiveArguments::of(
                    ['1.1', '1.2'],
                    ['2.1', 'default'],
                    ['3.1', '3.2'],
                )
            );

        $this->mock->multipleWithDefault('1.1', '1.2');
        $this->mock->multipleWithDefault('2.1');
        $this->mock->multipleWithDefault('3.1', '3.2');
    }

    public function testOnMethodWithVariadicParameter(): void
    {
        $this->mock
            ->expects(self::exactly(3))
            ->method('variadic')
            ->with(
                ...ConsecutiveArguments::of(
                    ['1.1', '1.2', '1.3'],
                    ['2.1', '2.2', '2.3'],
                    ['3.1', '3.2', '3.3'],
                )
            );

        $this->mock->variadic('1.1', '1.2', '1.3');
        $this->mock->variadic('2.1', '2.2', '2.3');
        $this->mock->variadic('3.1', '3.2', '3.3');
    }

    public function testOnMethodWithAnythingArguments(): void
    {
        $this->mock
            ->expects(self::exactly(3))
            ->method('variadic')
            ->with(
                ...ConsecutiveArguments::of(
                    [new IsAnything(), '1.2', '1.3'],
                    ['2.1', new IsAnything(), '2.3'],
                    ['3.1', '3.2', new IsAnything()],
                )
            );

        $this->mock->variadic('1.1', '1.2', '1.3');
        $this->mock->variadic('2.1', '2.2', '2.3');
        $this->mock->variadic('3.1', '3.2', '3.3');
    }

    public function testOnMethodWithMoreExecutionsThanDefined(): void
    {
        $this->mock
            ->expects(self::exactly(3))
            ->method('variadic')
            ->with(
                ...ConsecutiveArguments::of(
                    ['1.1', '1.2', '1.3'],
                )
            );

        $this->mock->variadic('1.1', '1.2', '1.3');
        $this->mock->variadic('2.1', '2.2', '2.3');
        $this->mock->variadic('3.1', '3.2', '3.3');
    }
}
