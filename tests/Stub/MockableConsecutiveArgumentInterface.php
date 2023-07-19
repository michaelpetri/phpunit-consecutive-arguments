<?php

declare(strict_types=1);

namespace Tests\MichaelPetri\PhpunitConsecutiveArguments\Stub;

interface MockableConsecutiveArgumentInterface
{
    public function single(mixed $arg1): void;

    public function singleOptional(mixed $arg1 = null): void;

    public function singleWithDefault(mixed $arg1 = 'default'): void;

    public function multiple(mixed $arg1, mixed $arg2): void;

    public function multipleWithOptional(mixed $arg1, mixed $arg2 = null): void;

    public function multipleWithDefault(mixed $arg1, mixed $arg2 = 'default'): void;

    public function variadic(mixed ...$arguments): void;
}
