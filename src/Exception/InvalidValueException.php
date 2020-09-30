<?php
declare(strict_types=1);

namespace CuyZ\MagicConstant\Exception;

use InvalidArgumentException;

final class InvalidValueException extends InvalidArgumentException implements MagicConstantException
{
    /**
     * @param string $className
     * @param mixed $value
     */
    public function __construct(string $className, $value)
    {
        if (is_object($value)) {
            $value = get_class($value);
        }

        parent::__construct(sprintf('The value `%s` does not exist in `%s`', (string)$value, $className));
    }
}
