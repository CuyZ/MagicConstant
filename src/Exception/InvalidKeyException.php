<?php
declare(strict_types=1);

namespace CuyZ\MagicConstant\Exception;

use InvalidArgumentException;

/**
 * @codeCoverageIgnore
 */
final class InvalidKeyException extends InvalidArgumentException implements MagicConstantException
{
    public function __construct(string $className, string $key)
    {
        parent::__construct(sprintf('The key `%s` does not exist in `%s`', $key, $className));
    }
}
