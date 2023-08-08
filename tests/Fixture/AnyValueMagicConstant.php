<?php

declare(strict_types=1);

namespace CuyZ\MagicConstant\Tests\Fixture;

use CuyZ\MagicConstant\MagicConstant;

final class AnyValueMagicConstant extends MagicConstant
{
    protected function setValue(mixed $value): void
    {
        $this->value = $value;
    }
}
