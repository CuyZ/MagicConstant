<?php
declare(strict_types=1);

namespace CuyZ\MagicConstant\Tests\Fixture;

use CuyZ\MagicConstant\MagicConstant;

/**
 * @method static OtherMagicConstant OTHER()
 */
final class OtherMagicConstant extends MagicConstant
{
    protected const OTHER = 'other';
}
