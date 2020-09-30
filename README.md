# Magic Constant

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Software License][ico-license]](LICENSE)

This library allows you to create enum-like classes that support multiple
formats for each key.

It helps represent [magic numbers and strings][link-wikipedia] in code.

## Example

Let's say your code has to interact with two services about some contracts.

To represent an active contract:

- Service A uses `active`
- Service B uses `10`

Using a magic constant you declare the following class:

```php
use CuyZ\MagicConstant\MagicConstant;

class ContractStatus extends MagicConstant
{
    protected const ACTIVE = [
        self::FORMAT_SERVICE_A => 'active',
        self::FORMAT_SERVICE_B => 10,
    ];

    // Others status...

    public const FORMAT_SERVICE_A = 'a';
    public const FORMAT_SERVICE_B = 'b';
}
```

You can then use it like this:

```php
// Instead of doing this:
if ($status === 'active' || $status === 10) {
    //
}

// You can do this:
if ($status->equals(ContractStatus::ACTIVE())) {
    //
}
```

##Installation

```bash
$ composer require cuyz/magic-constant
```

## Usage

```php
use CuyZ\MagicConstant\MagicConstant;

/**
 * You can declare static methods to help with autocompletion:
 *
 * @method static Example FOO()
 * @method static Example BAR()
 * @method static Example FIZ()
 */
class Example extends MagicConstant
{
    // Only protected constants are used as keys
    protected const FOO = 'foo';

    // A key can have multiple possible formats for it's value
    protected const BAR = ['bar', 'BAR', 'b'];

    // You can use an associative array to declare formats
    protected const FIZ = [
        self::FORMAT_LOWER => 'fiz',
        self::FORMAT_UPPER => 'FIZ',
    ];

    // Using constants for formats is not mandatory
    public const FORMAT_LOWER = 'lower';
    public const FORMAT_UPPER = 'upper';
}
```

You can then use the class everywhere:

```php
function hello(Example $example) {
    //
}

hello(new Example('foo'));

// You can also use constants keys as a static method
hello(Example::BAR());
```

## Methods

```php
echo (new Example('foo'))->getValue(); // 'foo'

echo (new Example('FIZ'))->getValue(Example::FORMAT_LOWER); // 'fiz'
```

```php
$constant = new Example('b');

echo $constant->getKey(); // 'BAR'
```

```php
$constant = new Example('fiz');

echo $constant->getAllFormats(); // [new Example('fiz'), new Example('FIZ')]
```

```php
$constant = new Example('BAR');

echo $constant->getAllValues(); // ['bar', 'BAR', 'b']
```

```php
$constant = new Example('BAR');

echo $constant->normalize(); // new Example('bar')
```

```php
(new Example('foo'))->equals(new Exemple('bar')); // false

(new Example('fiz'))->equals(new Exemple('FIZ')); // true
(new Example('b'))->equals(new Exemple('b')); // true
```

```php
$constant = new Example('foo');

$constant->in([new Exemple('bar'), null, 'foo']); // false
$constant->in([new Exemple('foo'), null, 'foo']); // true
```

```php
Example::keys(); // ['FOO', 'BAR', 'FIZ']
```

```php
Example::values();

[
    'FOO' => new Example('foo'),
    'BAR' => new Example('bar'),
    'FIZ' => new Example('fiz'),
];

// You can specify a regex pattern to match certain keys
Example::values('/^F(.+)/');

[
    'FOO' => new Example('foo'),
    'FIZ' => new Example('fiz'),
];
```

```php
Example::toArray();

[
    'FOO' => ['foo'],
    'BAR' => ['bar', 'BAR', 'b'],
    'FIZ' => ['fiz', 'FIZ'],
];
```

```php
Example::isValidValue('foo'); // true
Example::isValidValue('hello'); // false
```

```php
Example::isValidKey('BAR'); // true
Example::isValidKey('HELLO'); // false
```

```php
Example::search('foo'); // 'FOO'
Example::search('b'); // 'BAR'
Example::search('hello'); // false
```

[ico-version]: https://img.shields.io/packagist/v/cuyz/magic-constant.svg
[ico-downloads]: https://img.shields.io/packagist/dt/cuyz/magic-constant.svg
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg

[link-packagist]: https://packagist.org/packages/cuyz/magic-constant
[link-downloads]: https://packagist.org/packages/cuyz/magic-constant
[link-wikipedia]: https://en.wikipedia.org/wiki/Magic_number_(programming)
