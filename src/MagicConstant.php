<?php
declare(strict_types=1);

namespace CuyZ\MagicConstant;

use CuyZ\MagicConstant\Exception\InvalidFormatException;
use CuyZ\MagicConstant\Exception\InvalidKeyException;
use CuyZ\MagicConstant\Exception\InvalidValueException;
use ReflectionClass;

abstract class MagicConstant
{
    /** @var mixed */
    protected $value;

    /** @var array */
    protected static $cache = [];

    /**
     * @param mixed $value
     */
    final public function __construct($value)
    {
        if ($value instanceof self) {
            $value = $value->getValue();
        }

        $this->setValue($value);
    }

    /**
     * @param string|null $format
     * @return mixed
     */
    public function getValue(string $format = null)
    {
        if (empty($format)) {
            return $this->value;
        }

        $values = static::toArray();

        if (!isset($values[$this->getKey()][$format])) {
            throw new InvalidFormatException($this, $format);
        }

        return $values[$this->getKey()][$format];
    }

    /**
     * @return static[]
     */
    public function getAllFormats(): array
    {
        $values = static::toArray();
        $instances = array_map(
            function ($value) {
                return new static($value);
            },
            $values[$this->getKey()]
        );

        return array_values($instances);
    }

    /**
     * @return mixed[]
     */
    public function getAllValues(): array
    {
        $values = static::toArray();

        return array_values($values[$this->getKey()]);
    }

    public function getKey(): string
    {
        return (string)static::search($this->value);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * @return MagicConstant
     */
    public function normalize(): MagicConstant
    {
        $array = static::toArray();
        $key = static::search($this->value);

        reset($array);

        return new static(current($array[$key]));
    }

    /**
     * @param mixed $value
     */
    protected function setValue($value): void
    {
        if (!static::isValidValue($value)) {
            throw new InvalidValueException(static::class, $value);
        }

        $this->value = $value;
    }

    final public function equals(?MagicConstant $other): bool
    {
        if ($other === null) {
            return false;
        }

        if (get_called_class() !== get_class($other)) {
            return false;
        }

        $ownKey = static::search($this->value);
        $otherKey = static::search($other->getValue());

        if (false === $ownKey || false === $otherKey) {
            return false;
        }

        return $ownKey === $otherKey;
    }

    /**
     * @param mixed[] $values
     * @return bool
     */
    public function in(array $values): bool
    {
        foreach ($values as $value) {
            if (!($value instanceof static)) {
                continue;
            }

            if ($this->equals($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $format
     * @return static
     */
    public function toFormat(string $format): self
    {
        return new static($this->getValue($format));
    }

    /**
     * @return string[]
     */
    public static function keys(): array
    {
        return array_keys(static::toArray());
    }

    /**
     * @param string|null $pattern
     * @return static[]
     */
    public static function values(string $pattern = null): array
    {
        $out = [];

        foreach (static::toArray() as $key => $values) {
            if (null === $pattern || preg_match($pattern, $key)) {
                $out[$key] = new static(reset($values));
            }
        }

        return $out;
    }

    /**
     * @return array
     */
    public static function toArray(): array
    {
        if (!array_key_exists(static::class, static::$cache)) {
            $reflection = new ReflectionClass(static::class);
            $constants = $reflection->getReflectionConstants();

            $cache = [];

            foreach ($constants as $constant) {
                if (!$constant->isProtected()) {
                    continue;
                }

                $value = $constant->getValue();

                if (!is_array($value)) {
                    $value = [$value];
                }

                $cache[$constant->name] = $value;
            }

            static::$cache[static::class] = $cache;
        }

        return static::$cache[static::class];
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function isValidValue($value): bool
    {
        return false !== static::search($value);
    }

    /**
     * @param mixed $key
     * @return bool
     */
    public static function isValidKey($key): bool
    {
        $array = static::toArray();

        return isset($array[$key]);
    }

    /**
     * @param mixed $value
     * @return bool|string
     */
    public static function search($value)
    {
        foreach (static::toArray() as $constant => $values) {
            if (in_array($value, $values, true)) {
                return $constant;
            }
        }

        return false;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return static
     * @throws InvalidKeyException
     */
    public static function __callStatic(string $name, array $arguments = [])
    {
        $array = static::toArray();

        if (!isset($array[$name])) {
            throw new InvalidKeyException(static::class, $name);
        }

        return new static(reset($array[$name]));
    }
}
