<?php

declare(strict_types=1);

namespace MichaelPetri\PhpunitConsecutiveArguments;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ConsecutiveArgumentsRectorRule extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [
            Node\Expr\MethodCall::class
        ];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof Node\Expr\MethodCall) {
            return null;
        }

        if (
            $node->name instanceof Node\Identifier &&
            $node->name->name !== 'withConsecutive'
        ) {
            return null;
        }

        $node->name = new Node\Identifier('with');

        $node->args = [
            new Node\Arg(
                new Node\Expr\StaticCall(
                    new Node\Name(
                        '\\' . ConsecutiveArguments::class
                    ),
                    new Node\Identifier('of'),
                    $node->args,
                ),
                unpack: true,
            )
        ];

        return $node;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Use $mock->with(...ConsecutiveArguments::of(...)) instead of $mock->withConsecutive(...)',
            [
                new CodeSample(
                    <<<PHP
use MichaelPetri\PhpunitConsecutiveArguments\ConsecutiveArguments;
use PhpParser\Node;
use PHPUnit\Framework\TestCase;

final class GoodExample extends TestCase
{
    public function testExample(): void
    {
        \$mock = \$this->createMock(Node::class);

        \$mock
            ->expects(self::exactly(2))
            ->method('hasAttribute')
            ->with(
                ...ConsecutiveArguments::of(
                    ['foo'],
                    ['bar']
                )
            )
            ->willReturn(true);

        \$mock->hasAttribute('foo');
        \$mock->hasAttribute('bar');
    }
}
PHP,
                    <<<PHP
use PhpParser\Node;
use PHPUnit\Framework\TestCase;

final class BadExample extends TestCase
{
    public function testExample(): void
    {
        \$mock = \$this->createMock(Node::class);

        \$mock
            ->expects(self::exactly(2))
            ->method('hasAttribute')
            ->withConsecutive(
                ['foo'],
                ['bar']
            )
            ->willReturn(true);

        \$mock->hasAttribute('foo');
        \$mock->hasAttribute('bar');
    }
}
PHP
                )
            ]
        );
    }
}
