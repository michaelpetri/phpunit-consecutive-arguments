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

## PHPUnit version notes

- PHPUnit 10–12: [`withConsecutive()` was removed](https://github.com/sebastianbergmann/phpunit/issues/5063), so this library provides a drop-in replacement (see the [PHPUnit 9.6 deprecations note](https://github.com/sebastianbergmann/phpunit/blob/9.6/DEPRECATIONS.md)).
- PHPUnit 13+: PHPUnit ships a native alternative [`withParameterSetsInOrder()`](https://github.com/sebastianbergmann/phpunit-documentation-english/issues/405), announced in [PHPUnit 13.0](https://phpunit.de/announcements/phpunit-13.html).

If you only target PHPUnit 13 or newer, use the built-in methods. This package stays useful when you need one codebase that runs on both PHPUnit 10–12 and 13+.
