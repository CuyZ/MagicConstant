<?php
declare(strict_types=1);

namespace CuyZ\MagicConstant\Tests\Fixture;

use CuyZ\MagicConstant\MagicConstant;

/**
 * @method static FakeMagicConstant TYPE_STRING()
 * @method static FakeMagicConstant TYPE_INTEGER()
 * @method static FakeMagicConstant TYPE_ARRAY_SINGLE()
 * @method static FakeMagicConstant TYPE_ARRAY_MULTIPLE()
 * @method static FakeMagicConstant TYPE_ARRAY_FORMATS()
 */
final class FakeMagicConstant extends MagicConstant
{
    protected const TYPE_STRING = 'foo';
    protected const TYPE_INTEGER = 123;
    protected const TYPE_ARRAY_SINGLE = ['bar'];
    protected const TYPE_ARRAY_MULTIPLE = ['A', 'B', 'C'];
    protected const TYPE_ARRAY_FORMATS = [
        self::FORMAT_A => 'value A',
        self::FORMAT_B => 'value B',
        self::FORMAT_C => 'value C',
    ];

    public const FORMAT_A = 'format A';
    public const FORMAT_B = 'format B';
    public const FORMAT_C = 'format C';
}
