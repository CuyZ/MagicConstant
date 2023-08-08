<?php
declare(strict_types=1);

namespace CuyZ\MagicConstant\Tests\Fixture;

use CuyZ\MagicConstant\MagicConstant;

final class CustomSetValueMagicConstant extends MagicConstant
{
    protected const A = 'foo';

    protected function setValue(mixed $value): void
    {
        parent::setValue(strtolower($value));
    }
}
