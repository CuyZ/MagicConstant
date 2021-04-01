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
        $data = [];

        foreach ($magicConstantClass::values() as $value) {
            foreach ($value->getAllFormats() as $constant) {
                $data[] = [
                    new $magicConstantClass($constant->getValue()),
                    $constant->getKey(),
                    $constant->getValue(),
                    $constant->getFormat(),
                ];
            }
        }

        return $data;
    }
}
