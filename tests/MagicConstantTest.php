<?php

namespace CuyZ\MagicConstant\Tests;

use CuyZ\MagicConstant\Exception\InvalidFormatException;
use CuyZ\MagicConstant\Exception\InvalidKeyException;
use CuyZ\MagicConstant\Exception\InvalidValueException;
use CuyZ\MagicConstant\MagicConstant;
use CuyZ\MagicConstant\Tests\Fixture\CustomSetValueMagicConstant;
use CuyZ\MagicConstant\Tests\Fixture\AnyValueMagicConstant;
use CuyZ\MagicConstant\Tests\Fixture\FakeMagicConstant;
use CuyZ\MagicConstant\Tests\Fixture\OtherMagicConstant;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use stdClass;

class MagicConstantTest extends TestCase
{
    #[Test]
    public function the_constructor_throws_for_an_invalid_value(): void
    {
        /* *** Assertion *** */
        $this->expectException(InvalidValueException::class);

        /* *** Initialisation *** */
        $wrongValue = 'wrong value';

        /* *** Process *** */
        new FakeMagicConstant($wrongValue);
    }

    #[Test]
    public function the_constructor_throws_for_other_magic_constant_instance(): void
    {
        /* *** Assertion *** */
        $this->expectException(InvalidValueException::class);

        /* *** Initialisation *** */
        $wrongValue = OtherMagicConstant::OTHER();

        /* *** Process *** */
        new FakeMagicConstant($wrongValue);
    }

    #[Test]
    public function throws_for_wrong_key(): void
    {
        /* *** Assertion *** */
        $this->expectException(InvalidKeyException::class);

        /* *** Process *** */
        /** @noinspection PhpUndefinedMethodInspection */
        FakeMagicConstant::WRONG_KEY(); // @phpstan-ignore-line
    }

    #[Test]
    public function values_are_case_sensitive(): void
    {
        /* *** Initialisation *** */
        $this->expectException(InvalidValueException::class);

        /* *** Process *** */
        $wrongValue = 'FOO';

        /* *** Assertion *** */
        new FakeMagicConstant($wrongValue);
    }

    #[Test]
    public function getValue_throws_for_an_invalid_format(): void
    {
        /* *** Assertion *** */
        $this->expectException(InvalidFormatException::class);

        /* *** Initialisation *** */
        $magicConstant = FakeMagicConstant::TYPE_STRING();

        /* *** Process *** */
        $magicConstant->getValue('wrong format');
    }

    #[Test]
    public function create_all_possible_values_from_the_constructor(): void
    {
        /* *** Process *** */
        self::assertSame('foo', (new FakeMagicConstant('foo'))->getValue());
        self::assertSame(123, (new FakeMagicConstant(123))->getValue());
        self::assertSame('bar', (new FakeMagicConstant('bar'))->getValue());
        self::assertSame('A', (new FakeMagicConstant('A'))->getValue());
        self::assertSame('B', (new FakeMagicConstant('B'))->getValue());
        self::assertSame('C', (new FakeMagicConstant('C'))->getValue());
        self::assertSame('value A', (new FakeMagicConstant('value A'))->getValue());
        self::assertSame('value B', (new FakeMagicConstant('value B'))->getValue());
        self::assertSame('value C', (new FakeMagicConstant('value C'))->getValue());
    }

    #[Test]
    public function create_all_possible_values_from_the_static_method(): void
    {
        /* *** Process *** */
        self::assertSame('foo', FakeMagicConstant::TYPE_STRING()->getValue());
        self::assertSame(123, FakeMagicConstant::TYPE_INTEGER()->getValue());
        self::assertSame('bar', FakeMagicConstant::TYPE_ARRAY_SINGLE()->getValue());
        self::assertSame('A', FakeMagicConstant::TYPE_ARRAY_MULTIPLE()->getValue());
        self::assertSame('value A', FakeMagicConstant::TYPE_ARRAY_FORMATS()->getValue());
    }

    #[Test]
    public function create_instance_from_another_instance(): void
    {
        /* *** Initialisation *** */
        $constants = FakeMagicConstant::values();

        /* *** Process *** */
        foreach ($constants as $constant => $instance) {
            $magicConstant = new FakeMagicConstant($instance);

            /* *** Assertion *** */
            self::assertTrue($magicConstant->equals($instance));
        }
    }

    #[Test]
    #[DataProvider('fakeMagicConstantDataProvider')]
    public function getValue_returns_the_correct_value(FakeMagicConstant $magicConstant): void
    {
        /* *** Process *** */
        $actualMagicConstant = new FakeMagicConstant($magicConstant->getValue());

        /* *** Assertion *** */
        self::assertSame($magicConstant->getValue(), $actualMagicConstant->getValue());
    }

    #[Test]
    #[DataProvider('fakeMagicConstantDataProvider')]
    public function getValue_returns_the_correct_value_depending_on_the_format(
        FakeMagicConstant $magicConstant,
        int|string $key,
        mixed $expectedValue,
        int|string $format
    ): void {
        /* *** Process *** */
        $actualMagicConstant = new FakeMagicConstant($magicConstant->getValue());
        $actualValue = $actualMagicConstant->getValue($format);

        /* *** Assertion *** */
        self::assertSame($expectedValue, $actualValue);
    }

    #[Test]
    #[DataProvider('fakeMagicConstantDataProvider')]
    public function getKey_returns_the_correct_value(FakeMagicConstant $magicConstant, string $expectedKey): void
    {
        /* *** Process *** */
        $actualMagicConstant = new FakeMagicConstant($magicConstant->getValue());

        /* *** Assertion *** */
        self::assertSame($expectedKey, $actualMagicConstant->getKey());
    }

    #[Test]
    public function getKey_returns_empty_string_for_dynamic_value(): void
    {
        $constant = new AnyValueMagicConstant('foo');

        self::assertSame('', $constant->getKey());
    }

    #[Test]
    #[DataProvider('fakeMagicConstantDataProvider')]
    public function toString_returns_the_correct_value(
        FakeMagicConstant $magicConstant,
        int|string $key,
        mixed $expectedValue
    ): void {
        /* *** Process *** */
        $actualValue = (string)$magicConstant;

        /* *** Assertion *** */
        self::assertSame((string)$expectedValue, $actualValue);
    }

    #[Test]
    public function keys_returns_the_list_of_possible_keys(): void
    {
        /* *** Initialisation *** */
        $expectedConstants = [
            'TYPE_STRING',
            'TYPE_INTEGER',
            'TYPE_ARRAY_SINGLE',
            'TYPE_ARRAY_MULTIPLE',
            'TYPE_ARRAY_FORMATS',
        ];

        /* *** Process *** */
        $actualConstants = FakeMagicConstant::keys();

        /* *** Assertion *** */
        self::assertSame($expectedConstants, $actualConstants);
    }

    #[Test]
    public function values_returns_an_array_of_possible_values(): void
    {
        /* *** Initialisation *** */
        $extectedValues = [
            'TYPE_STRING' => new FakeMagicConstant('foo'),
            'TYPE_INTEGER' => new FakeMagicConstant(123),
            'TYPE_ARRAY_SINGLE' => new FakeMagicConstant('bar'),
            'TYPE_ARRAY_MULTIPLE' => new FakeMagicConstant('A'),
            'TYPE_ARRAY_FORMATS' => new FakeMagicConstant('value A'),
        ];

        /* *** Process *** */
        $actualValues = FakeMagicConstant::values();

        /* *** Assertion *** */
        self::assertEquals($extectedValues, $actualValues);
    }

    #[Test]
    #[DataProvider('isValidValueDataProvider')]

    public function isValidValue_checks_if_a_value_is_valid(mixed $value, bool $isValid): void
    {
        /* *** Process *** */
        $actualIsValid = FakeMagicConstant::isValidValue($value);

        /* *** Assertion *** */
        self::assertSame($isValid, $actualIsValid);
    }

    public static function isValidValueDataProvider(): array
    {
        return [
            // Valid values
            ['foo', true],
            [123, true],
            ['bar', true],
            ['A', true],
            ['B', true],
            ['C', true],

            // Invalid values
            ['invalid', false],
        ];
    }

    #[Test]
    #[DataProvider('isValidKeyDataProvider')]
    public function isValidKey_checks_if_a_key_is_valid(mixed $key, bool $isValid): void
    {
        /* *** Process *** */
        $actualIsValid = FakeMagicConstant::isValidKey($key);

        /* *** Assertion *** */
        self::assertSame($isValid, $actualIsValid);
    }

    public static function isValidKeyDataProvider(): array
    {
        return [
            // Valid keys
            ['TYPE_STRING', true],
            ['TYPE_INTEGER', true],
            ['TYPE_ARRAY_SINGLE', true],
            ['TYPE_ARRAY_MULTIPLE', true],

            // Invalid keys
            ['INVALID', false],
        ];
    }

    #[Test]
    #[DataProvider('equalsDataProvider')]
    public function equals_compares_values(MagicConstant $magicConstantA, mixed $magicConstantB, bool $expectedResult): void
    {
        /* *** Process *** */
        $actualResult = $magicConstantA->equals($magicConstantB);

        /* *** Assertion *** */
        self::assertSame($expectedResult, $actualResult);
    }

    public static function equalsDataProvider(): array
    {
        return [
            [new FakeMagicConstant('foo'), new FakeMagicConstant('foo'), true],
            [new FakeMagicConstant(123), new FakeMagicConstant(123), true],
            [new FakeMagicConstant('bar'), new FakeMagicConstant('bar'), true],
            [new FakeMagicConstant('A'), new FakeMagicConstant('A'), true],
            [new FakeMagicConstant('A'), new FakeMagicConstant('B'), true],
            [new FakeMagicConstant('A'), new FakeMagicConstant('C'), true],
            [new FakeMagicConstant('B'), new FakeMagicConstant('B'), true],
            [new FakeMagicConstant('B'), new FakeMagicConstant('C'), true],
            [new FakeMagicConstant('C'), new FakeMagicConstant('C'), true],

            [new FakeMagicConstant('foo'), new FakeMagicConstant('A'), false],
            [new FakeMagicConstant(123), new FakeMagicConstant('A'), false],
            [new FakeMagicConstant('bar'), new FakeMagicConstant('A'), false],
            [new FakeMagicConstant('A'), new FakeMagicConstant('foo'), false],
            [new FakeMagicConstant('B'), new FakeMagicConstant('foo'), false],
            [new FakeMagicConstant('C'), new FakeMagicConstant('foo'), false],

            [new FakeMagicConstant('foo'), null, false],
            [new FakeMagicConstant('foo'), new OtherMagicConstant('other'), false],
            [new FakeMagicConstant('foo'), new OtherMagicConstant('foo'), false],
        ];
    }

    #[Test]
    #[DataProvider('inDataProvider')]
    public function in_returns_true_if_at_least_one_value_is_correct(MagicConstant $magicConstant, array $values, bool $expectedResult): void
    {
        self::assertSame($expectedResult, $magicConstant->in($values));
        self::assertSame($expectedResult, $magicConstant->in(array_reverse($values)));
    }

    public static function inDataProvider(): array
    {
        return [
            [new FakeMagicConstant('foo'), [new FakeMagicConstant('foo')], true],
            [new FakeMagicConstant('foo'), [new FakeMagicConstant('foo'), ''], true],
            [new FakeMagicConstant('foo'), [new FakeMagicConstant('foo'), true], true],
            [new FakeMagicConstant('foo'), [new FakeMagicConstant('foo'), false], true],
            [new FakeMagicConstant('foo'), [new FakeMagicConstant('foo'), null], true],
            [new FakeMagicConstant('foo'), [new FakeMagicConstant('foo'), 123], true],
            [new FakeMagicConstant(123), [new FakeMagicConstant(123)], true],
            [new FakeMagicConstant('bar'), [new FakeMagicConstant('bar')], true],
            [new FakeMagicConstant('A'), [new FakeMagicConstant('A')], true],
            [new FakeMagicConstant('A'), [new FakeMagicConstant('B')], true],
            [new FakeMagicConstant('A'), [new FakeMagicConstant('C')], true],
            [new FakeMagicConstant('B'), [new FakeMagicConstant('B')], true],
            [new FakeMagicConstant('B'), [new FakeMagicConstant('C')], true],
            [new FakeMagicConstant('C'), [new FakeMagicConstant('C')], true],

            [new FakeMagicConstant('A'), [], false],
            [new FakeMagicConstant('A'), [''], false],
            [new FakeMagicConstant('A'), [true], false],
            [new FakeMagicConstant('A'), [false], false],
            [new FakeMagicConstant('A'), [null], false],
            [new FakeMagicConstant('A'), [123], false],
            [new FakeMagicConstant('A'), [new FakeMagicConstant('foo')], false],
            [new FakeMagicConstant('A'), [new FakeMagicConstant(123)], false],
            [new FakeMagicConstant('A'), [new FakeMagicConstant('bar')], false],
        ];
    }

    public static function fakeMagicConstantDataProvider(): array
    {
        return self::magicConstantDataProvider(FakeMagicConstant::class);
    }

    #[Test]
    #[DataProvider('allFormatsDataProvider')]
    public function getAllFormats_returns_instances_in_all_possible_formats(MagicConstant $magicConstant, array $expectedValues): void
    {
        /* *** Process *** */
        $actualValues = $magicConstant->getAllFormats();

        /* *** Assertion *** */
        self::assertEquals($expectedValues, $actualValues);
    }

    public static function allFormatsDataProvider(): array
    {
        return [
            [
                'magicConstant' => FakeMagicConstant::TYPE_STRING(),
                'expectedValues' => [FakeMagicConstant::TYPE_STRING()],
            ],
            [
                'magicConstant' => FakeMagicConstant::TYPE_INTEGER(),
                'expectedValues' => [FakeMagicConstant::TYPE_INTEGER()],
            ],
            [
                'magicConstant' => FakeMagicConstant::TYPE_ARRAY_SINGLE(),
                'expectedValues' => [FakeMagicConstant::TYPE_ARRAY_SINGLE()],
            ],
            [
                'magicConstant' => FakeMagicConstant::TYPE_ARRAY_MULTIPLE(),
                'expectedValues' => [new FakeMagicConstant('A'), new FakeMagicConstant('B'), new FakeMagicConstant('C')],
            ],
            [
                'magicConstant' => FakeMagicConstant::TYPE_ARRAY_FORMATS(),
                'expectedValues' => [new FakeMagicConstant('value A'), new FakeMagicConstant('value B'), new FakeMagicConstant('value C')],
            ],
        ];
    }

    #[Test]
    public function get_new_instance_in_specific_format(): void
    {
        /* *** Initialisation *** */
        $base = FakeMagicConstant::TYPE_ARRAY_FORMATS();

        /* *** Process *** */
        $instanceFormatA = $base->toFormat(FakeMagicConstant::FORMAT_A);
        $instanceFormatB = $base->toFormat(FakeMagicConstant::FORMAT_B);
        $instanceFormatC = $base->toFormat(FakeMagicConstant::FORMAT_C);

        /* *** Assertion *** */
        self::assertSame('value A', $instanceFormatA->getValue());
        self::assertSame('value B', $instanceFormatB->getValue());
        self::assertSame('value C', $instanceFormatC->getValue());
    }

    #[Test]
    public function normalize_resets_an_instance_to_the_first_format(): void
    {
        /* *** Initialisation *** */
        $withoutFormats = new FakeMagicConstant('B');
        $withFormats = new FakeMagicConstant('value C');

        /* *** Process *** */
        $withoutFormatsNormalized = $withoutFormats->normalize();
        $withFormatsNormalized = $withFormats->normalize();

        /* *** Assertion *** */
        self::assertSame('A', $withoutFormatsNormalized->getValue());
        self::assertSame('value A', $withFormatsNormalized->getValue());

        self::assertNotSame($withoutFormats, $withoutFormatsNormalized);
        self::assertNotSame($withFormats, $withFormatsNormalized);
    }

    public static function allValuesDataProvider(): array
    {
        return [
            [
                'magicConstant' => FakeMagicConstant::TYPE_STRING(),
                'expectedValues' => ['foo'],
            ],
            [
                'magicConstant' => FakeMagicConstant::TYPE_INTEGER(),
                'expectedValues' => [123],
            ],
            [
                'magicConstant' => FakeMagicConstant::TYPE_ARRAY_SINGLE(),
                'expectedValues' => ['bar'],
            ],
            [
                'magicConstant' => FakeMagicConstant::TYPE_ARRAY_MULTIPLE(),
                'expectedValues' => ['A', 'B', 'C'],
            ],
            [
                'magicConstant' => FakeMagicConstant::TYPE_ARRAY_FORMATS(),
                'expectedValues' => ['value A', 'value B', 'value C'],
            ],
        ];
    }

    #[Test]
    #[DataProvider('allValuesDataProvider')]
    public function return_values_in_all_formats(MagicConstant $magicConstant, array $expectedValues): void
    {
        /* *** Process *** */
        $actualValues = $magicConstant->getAllValues();

        /* *** Assertion *** */
        self::assertSame($expectedValues, $actualValues);
    }

    #[Test]
    public function has_custom_value_setter(): void
    {
        $magicConstant1 = new CustomSetValueMagicConstant('FOO');
        $magicConstant2 = new CustomSetValueMagicConstant('foo');

        self::assertSame('foo', $magicConstant1->getValue());
        self::assertSame('foo', $magicConstant2->getValue());
    }

    #[Test]
    public function getFormat_returns_the_instance_format(): void
    {
        self::assertEquals(0, (new FakeMagicConstant('foo'))->getFormat());
        self::assertEquals(0, (new FakeMagicConstant(123))->getFormat());
        self::assertEquals(0, (new FakeMagicConstant('bar'))->getFormat());

        self::assertEquals(0, (new FakeMagicConstant('A'))->getFormat());
        self::assertEquals(1, (new FakeMagicConstant('B'))->getFormat());
        self::assertEquals(2, (new FakeMagicConstant('C'))->getFormat());

        self::assertEquals('format A', (new FakeMagicConstant('value A'))->getFormat());
        self::assertEquals('format B', (new FakeMagicConstant('value B'))->getFormat());
        self::assertEquals('format C', (new FakeMagicConstant('value C'))->getFormat());
    }

    #[Test]
    public function tryFrom(): void
    {
        self::assertNull(FakeMagicConstant::tryFrom(null));
        self::assertNull(FakeMagicConstant::tryFrom(1234));
        self::assertNull(FakeMagicConstant::tryFrom('wrong'));
        self::assertNull(FakeMagicConstant::tryFrom(new stdClass()));

        self::assertInstanceOf(FakeMagicConstant::class, FakeMagicConstant::tryFrom('foo'));
        self::assertInstanceOf(FakeMagicConstant::class, FakeMagicConstant::tryFrom(123));
        self::assertInstanceOf(FakeMagicConstant::class, FakeMagicConstant::tryFrom('bar'));

        self::assertInstanceOf(FakeMagicConstant::class, FakeMagicConstant::tryFrom('A'));
        self::assertInstanceOf(FakeMagicConstant::class, FakeMagicConstant::tryFrom('B'));
        self::assertInstanceOf(FakeMagicConstant::class, FakeMagicConstant::tryFrom('C'));

        self::assertInstanceOf(FakeMagicConstant::class, FakeMagicConstant::tryFrom('value A'));
        self::assertInstanceOf(FakeMagicConstant::class, FakeMagicConstant::tryFrom('value B'));
        self::assertInstanceOf(FakeMagicConstant::class, FakeMagicConstant::tryFrom('value C'));

        self::assertInstanceOf(FakeMagicConstant::class, FakeMagicConstant::tryFrom(FakeMagicConstant::TYPE_STRING()));
    }
}
