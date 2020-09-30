<?php
declare(strict_types=1);

namespace CuyZ\MagicConstant\Tests;

use CuyZ\MagicConstant\MagicConstant;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @param string|MagicConstant $magicConstantClass
     * @return array
     */
    protected function magicConstantDataProvider(string $magicConstantClass): array
    {
        $constants = $magicConstantClass::toArray();

        $data = [];

        foreach ($constants as $key => $values) {
            foreach ($values as $format => $value) {
                $data[] = [new $magicConstantClass($value), $key, $value, $format];
            }
        }

        return $data;
    }
}
