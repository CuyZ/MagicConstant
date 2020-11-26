<?php
declare(strict_types=1);

namespace CuyZ\MagicConstant\Exception;

use CuyZ\MagicConstant\MagicConstant;
use InvalidArgumentException;

/**
 * @codeCoverageIgnore
 */
final class InvalidFormatException extends InvalidArgumentException implements MagicConstantException
{
    public function __construct(MagicConstant $magicConstant, string $format)
    {
        parent::__construct(sprintf('The format `%s` does not exist in `%s`', $format, get_class($magicConstant)));
    }
}
