<?php

namespace CuyZ\MagicConstant\Tests;

use CuyZ\MagicConstant\Exception\InvalidFormatException;
use CuyZ\MagicConstant\Exception\InvalidKeyException;
use CuyZ\MagicConstant\Exception\InvalidValueException;
use CuyZ\MagicConstant\MagicConstant;
use CuyZ\MagicConstant\Tests\Fixture\CustomSetValueMagicConstant;
use CuyZ\MagicConstant\Tests\Fixture\FakeMagicConstant;
use CuyZ\MagicConstant\Tests\Fixture\OtherMagicConstant;

class MagicConstantTest extends TestCase
{
    /** @test */
    public function the_constructor_throws_for_an_invalid_value()
    {
        /* *** Assertion *** */
        $this->expectException(InvalidValueException::class);

        /* *** Initialisation *** */
        $wrongValue = 'wrong value';

        /* *** Process *** */
        new FakeMagicConstant($wrongValue);
    }

    /** @test */
    public function the_constructor_throws_for_other_magic_constant_instance()
    {
        /* *** Assertion *** */
        $this->expectException(InvalidValueException::class);

        /* *** Initialisation *** */
        $wrongValue = OtherMagicConstant::OTHER();

        /* *** Process *** */
        new FakeMagicConstant($wrongValue);
    }

    /** @test */
    public function throws_for_wrong_key()
    {
        /* *** Assertion *** */
        $this->expectException(InvalidKeyException::class);

        /* *** Process *** */
        /** @noinspection PhpUndefinedMethodInspection */
        FakeMagicConstant::WRONG_KEY();
    }

    /** @test */
    public function values_are_case_sensitive()
    {
        /* *** Initialisation *** */
        $this->expectException(InvalidValueException::class);

        /* *** Process *** */
        $wrongValue = 'FOO';

        /* *** Assertion *** */
        new FakeMagicConstant($wrongValue);
    }

    /** @test */
    public function getValue_throws_for_an_invalid_format()
    {
        /* *** Assertion *** */
        $this->expectException(InvalidFormatException::class);

        /* *** Initialisation *** */
        $magicConstant = FakeMagicConstant::TYPE_STRING();

        /* *** Process *** */
        $magicConstant->getValue('wrong format');
    }

    /** @test */
    public function create_all_possible_values_from_the_constructor()
    {
        /* *** Initialisation *** */
        $constants = FakeMagicConstant::toArray();

        /* *** Process *** */
        foreach ($constants as $constant => $values) {
            foreach ($values as $value) {
                $magicConstant = new FakeMagicConstant($value);

                /* *** Assertion *** */
                self::assertSame($value, $magicConstant->getValue());
            }
        }
    }

    /** @test */
    public function create_all_possible_values_from_the_static_method()
    {
        /* *** Initialisation *** */
        $constants = FakeMagicConstant::toArray();

        /* *** Process *** */
        foreach ($constants as $constant => $values) {
            /** @var MagicConstant $magicConstant */
            $magicConstant = FakeMagicConstant::$constant();

            /* *** Assertion *** */
            self::assertSame(reset($values), $magicConstant->getValue());
        }
    }

    /** @test */
    public function create_instance_from_another_instance()
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

    /**
     * @test
     * @dataProvider fakeMagicConstantDataProvider
     * @param FakeMagicConstant $magicConstant
     */
    public function getValue_returns_the_correct_value(FakeMagicConstant $magicConstant)
    {
        /* *** Process *** */
        $actualMagicConstant = new FakeMagicConstant($magicConstant->getValue());

        /* *** Assertion *** */
        self::assertSame($magicConstant->getValue(), $actualMagicConstant->getValue());
    }

    /**
     * @test
     * @dataProvider fakeMagicConstantDataProvider
     * @param FakeMagicConstant $magicConstant
     * @param string|int $key
     * @param mixed $expectedValue
     * @param string|int $format
     */
    public function getValue_returns_the_correct_value_depending_on_the_format(FakeMagicConstant $magicConstant, $key, $expectedValue, $format)
    {
        /* *** Process *** */
        $actualMagicConstant = new FakeMagicConstant($magicConstant->getValue());
        $actualValue = $actualMagicConstant->getValue($format);

        /* *** Assertion *** */
        self::assertSame($expectedValue, $actualValue);
    }

    /**
     * @test
     * @dataProvider fakeMagicConstantDataProvider
     * @param FakeMagicConstant $magicConstant
     * @param string $expectedKey
     */
    public function getKey_returns_the_correct_value(FakeMagicConstant $magicConstant, string $expectedKey)
    {
        /* *** Process *** */
        $actualMagicConstant = new FakeMagicConstant($magicConstant->getValue());

        /* *** Assertion *** */
        self::assertSame($expectedKey, $actualMagicConstant->getKey());
    }

    /**
     * @test
     * @dataProvider fakeMagicConstantDataProvider
     * @param FakeMagicConstant $magicConstant
     * @param string|int $key
     * @param mixed $expectedValue
     */
    public function toString_returns_the_correct_value(FakeMagicConstant $magicConstant, $key, $expectedValue)
    {
        /* *** Process *** */
        $actualValue = (string)$magicConstant;

        /* *** Assertion *** */
        self::assertSame((string)$expectedValue, $actualValue);
    }

    /** @test */
    public function keys_returns_the_list_of_possible_keys()
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

    /** @test */
    public function values_returns_an_array_of_possible_values()
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

    /**
     * This test must be run in a separate process to avoid the memoization of values.
     * @runInSeparateProcess
     * @test
     */
    public function toArray_returns_an_associative_array_of_constants_and_their_values()
    {
        /* *** Initialisation *** */
        $expectedValues = [
            'TYPE_STRING' => ['foo'],
            'TYPE_INTEGER' => [123],
            'TYPE_ARRAY_SINGLE' => ['bar'],
            'TYPE_ARRAY_MULTIPLE' => ['A', 'B', 'C'],
            'TYPE_ARRAY_FORMATS' => [
                FakeMagicConstant::FORMAT_A => 'value A',
                FakeMagicConstant::FORMAT_B => 'value B',
                FakeMagicConstant::FORMAT_C => 'value C',
            ],
        ];

        /* *** Process *** */
        $actualValues = FakeMagicConstant::toArray();

        /* *** Assertion *** */
        self::assertSame($expectedValues, $actualValues);
    }

    /**
     * @test
     * @dataProvider isValidValueDataProvider
     * @param mixed $value
     * @param $isValid
     */
    public function isValidValue_checks_if_a_value_is_valid($value, bool $isValid)
    {
        /* *** Process *** */
        $actualIsValid = FakeMagicConstant::isValidValue($value);

        /* *** Assertion *** */
        self::assertSame($isValid, $actualIsValid);
    }

    /**
     * @return array
     */
    public function isValidValueDataProvider(): array
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

    /**
     * @test
     * @dataProvider isValidKeyDataProvider
     * @param mixed $key
     * @param $isValid
     */
    public function isValidKey_checks_if_a_key_is_valid($key, bool $isValid)
    {
        /* *** Process *** */
        $actualIsValid = FakeMagicConstant::isValidKey($key);

        /* *** Assertion *** */
        self::assertSame($isValid, $actualIsValid);
    }

    /**
     * @return array
     */
    public function isValidKeyDataProvider(): array
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

    /**
     * @test
     * @dataProvider searchDataProvider
     * @param mixed $value
     * @param mixed $expectedKey
     */
    public function search_finds_the_correct_key($value, $expectedKey)
    {
        /* *** Process *** */
        $actualKey = FakeMagicConstant::search($value);

        /* *** Assertion *** */
        self::assertSame($expectedKey, $actualKey);
    }

    /**
     * @return array
     */
    public function searchDataProvider(): array
    {
        return [
            // Valid
            ['foo', 'TYPE_STRING'],
            [123, 'TYPE_INTEGER'],
            ['bar', 'TYPE_ARRAY_SINGLE'],
            ['A', 'TYPE_ARRAY_MULTIPLE'],
            ['B', 'TYPE_ARRAY_MULTIPLE'],
            ['C', 'TYPE_ARRAY_MULTIPLE'],

            // Invalid
            ['invalid', false],
        ];
    }

    /**
     * @test
     * @dataProvider equalsDataProvider
     * @param MagicConstant $magicConstantA
     * @param mixed $magicConstantB
     * @param bool $expectedResult
     */
    public function equals_compares_values(MagicConstant $magicConstantA, $magicConstantB, bool $expectedResult)
    {
        /* *** Process *** */
        $actualResult = $magicConstantA->equals($magicConstantB);

        /* *** Assertion *** */
        self::assertSame($expectedResult, $actualResult);
    }

    public function equalsDataProvider(): array
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

    /**
     * @test
     * @dataProvider inDataProvider
     * @param MagicConstant $magicConstant
     * @param array $values
     * @param bool $expectedResult
     */
    public function in_returns_true_if_at_least_one_value_is_correct(MagicConstant $magicConstant, array $values, bool $expectedResult)
    {
        self::assertSame($expectedResult, $magicConstant->in($values));
        self::assertSame($expectedResult, $magicConstant->in(array_reverse($values)));
    }

    public function inDataProvider(): array
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

    public function fakeMagicConstantDataProvider(): array
    {
        return $this->magicConstantDataProvider(FakeMagicConstant::class);
    }

    /**
     * @test
     * @dataProvider allFormatsDataProvider
     * @param MagicConstant $magicConstant
     * @param array $expectedValues
     */
    public function getAllFormats_returns_instances_in_all_possible_formats(MagicConstant $magicConstant, array $expectedValues)
    {
        /* *** Process *** */
        $actualValues = $magicConstant->getAllFormats();

        /* *** Assertion *** */
        self::assertEquals($expectedValues, $actualValues);
    }

    /**
     * @return array
     */
    public function allFormatsDataProvider(): array
    {
        return [
            [
                'magicConstant' => FakeMagicConstant::TYPE_STRING(),
                'values' => [FakeMagicConstant::TYPE_STRING()],
            ],
            [
                'magicConstant' => FakeMagicConstant::TYPE_INTEGER(),
                'values' => [FakeMagicConstant::TYPE_INTEGER()],
            ],
            [
                'magicConstant' => FakeMagicConstant::TYPE_ARRAY_SINGLE(),
                'values' => [FakeMagicConstant::TYPE_ARRAY_SINGLE()],
            ],
            [
                'magicConstant' => FakeMagicConstant::TYPE_ARRAY_MULTIPLE(),
                'values' => [new FakeMagicConstant('A'), new FakeMagicConstant('B'), new FakeMagicConstant('C')],
            ],
            [
                'magicConstant' => FakeMagicConstant::TYPE_ARRAY_FORMATS(),
                'values' => [new FakeMagicConstant('value A'), new FakeMagicConstant('value B'), new FakeMagicConstant('value C')],
            ],
        ];
    }

    /** @test */
    public function get_new_instance_in_specific_format()
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

    public function test_normalize_resets_an_instance_to_the_first_format()
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

    public function allValuesDataProvider(): array
    {
        return [
            [
                'magicConstant' => FakeMagicConstant::TYPE_STRING(),
                'values' => ['foo'],
            ],
            [
                'magicConstant' => FakeMagicConstant::TYPE_INTEGER(),
                'values' => [123],
            ],
            [
                'magicConstant' => FakeMagicConstant::TYPE_ARRAY_SINGLE(),
                'values' => ['bar'],
            ],
            [
                'magicConstant' => FakeMagicConstant::TYPE_ARRAY_MULTIPLE(),
                'values' => ['A', 'B', 'C'],
            ],
            [
                'magicConstant' => FakeMagicConstant::TYPE_ARRAY_FORMATS(),
                'values' => ['value A', 'value B', 'value C'],
            ],
        ];
    }

    /**
     * @dataProvider allValuesDataProvider
     * @param MagicConstant $magicConstant
     * @param array $expectedValues
     */
    public function test_return_values_in_all_formats(MagicConstant $magicConstant, array $expectedValues)
    {
        /* *** Process *** */
        $actualValues = $magicConstant->getAllValues();

        /* *** Assertion *** */
        self::assertSame($expectedValues, $actualValues);
    }

    public function test_has_custom_value_setter()
    {
        $magicConstant1 = new CustomSetValueMagicConstant('FOO');
        $magicConstant2 = new CustomSetValueMagicConstant('foo');

        self::assertSame('foo', $magicConstant1->getValue());
        self::assertSame('foo', $magicConstant2->getValue());
    }
}
