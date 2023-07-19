<?php

declare(strict_types=1);

use PhpParser\Node;
use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{
    public function testExample(): void
    {
        $mock = $this->createMock(Node::class);

        $mock
            ->expects(self::exactly(2))
            ->method('hasAttribute')
            ->withConsecutive(
                ['foo'],
                ['bar']
            )
            ->willReturn(true);

        $mock->hasAttribute('foo');
        $mock->hasAttribute('bar');
    }
}