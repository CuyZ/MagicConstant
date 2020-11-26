<?php
declare(strict_types=1);

namespace CuyZ\MagicConstant\Tests\Fixture;

use CuyZ\MagicConstant\MagicConstant;

/**
 * @method static OtherMagicConstant OTHER()
 * @method static OtherMagicConstant FOO()
 */
final class OtherMagicConstant extends MagicConstant
{
    protected const OTHER = 'other';
    protected const FOO = 'foo';
}
