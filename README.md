# PHPUnit Consecutive Arguments

Replacement for the removed InvocationMocker::withConsecutive method.

[![Type Coverage](https://shepherd.dev/github/michaelpetri/phpunit-consecutive-arguments/coverage.svg)](https://shepherd.dev/github/michaelpetri/phpunit-consecutive-arguments)
[![Latest Stable Version](https://poser.pugx.org/michaelpetri/phpunit-consecutive-arguments/v)](https://packagist.org/packages/michaelpetri/phpunit-consecutive-arguments)
[![License](https://poser.pugx.org/michaelpetri/phpunit-consecutive-arguments/license)](https://packagist.org/packages/michaelpetri/phpunit-consecutive-arguments)

## Installation

```shell
composer require michaelpetri/phpunit-consecutive-arguments 
```

## Example

```php
        $mock
            ->expects(self::exactly(\count(2)))
            ->method('someMethod')
            ->with(
                ...ConsecutiveArguments::of(
                    ['1.1', '1.2'],
                    ['2.1', '2.2'],
            );
```

See [Tests](tests/ConsecutiveArgumentsTest.php) for more examples
