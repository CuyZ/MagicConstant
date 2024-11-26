<?php
declare(strict_types=1);

namespace CuyZ\MagicConstant;

use CuyZ\MagicConstant\Exception\InvalidFormatException;
use CuyZ\MagicConstant\Exception\InvalidKeyException;
use CuyZ\MagicConstant\Exception\InvalidValueException;
use CuyZ\MagicConstant\Exception\MagicConstantException;
use ReflectionClass;

use function array_keys;

abstract class MagicConstant
{
    protected mixed $value;

    /** @var array<class-string<static>, array<mixed>> */
    protected static array $cache = [];

    final public function __construct(mixed $value)
    {
        if ($value instanceof self) {
            $value = $value->getValue();
        }

        $this->setValue($value);
    }

    public function getValue(string|int|null $format = null): mixed
    {
        if (empty($format)) {
            return $this->value;
        }

        $values = self::toArray();

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
        $values = self::toArray();
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
        $values = self::toArray();

        return array_values($values[$this->getKey()]);
    }

    public function getKey(): string
    {
        return (string)self::search($this->value);
    }

    /**
     * Returns the current instance format.
     */
    public function getFormat(): int|string|null
    {
        $values = self::toArray();

        foreach ($values[$this->getKey()] as $format => $value) {
            if ($value === $this->value) {
                return $format;
            }
        }

        return null;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }

    public function normalize(): MagicConstant
    {
        $array = self::toArray();
        $key = $this->getKey();

        $values = array_values($array[$key]);

        return new static($values[0]);
    }

    protected function setValue(mixed $value): void
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

        $ownKey = $this->getKey();
        $otherKey = self::search($other->getValue());

        return $ownKey === $otherKey;
    }

    /**
     * @param mixed[] $values
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

    public function toFormat(string $format): static
    {
        return new static($this->getValue($format));
    }

    /**
     * @return string[]
     */
    public static function keys(): array
    {
        return array_keys(self::toArray());
    }

    /**
     * @return static[]
     */
    public static function values(?string $pattern = null): array
    {
        $out = [];

        foreach (self::toArray() as $key => $values) {
            if (null === $pattern || preg_match($pattern, $key)) {
                $out[$key] = new static(reset($values));
            }
        }

        return $out;
    }

    private static function toArray(): array
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

    public static function isValidValue(mixed $value): bool
    {
        return false !== self::search($value);
    }

    public static function isValidKey(mixed $key): bool
    {
        $array = self::toArray();

        return isset($array[$key]);
    }

    private static function search(mixed $value): string|false
    {
        /**
         * @var string $constant
         * @var array $values
         */
        foreach (self::toArray() as $constant => $values) {
            if (in_array($value, $values, true)) {
                return $constant;
            }
        }

        return false;
    }

    public static function tryFrom(mixed $value): ?self
    {
        try {
            return new static($value);
        } catch (MagicConstantException $e) {
            return null;
        }
    }

    /**
     * @param array $arguments
     * @throws InvalidKeyException
     */
    public static function __callStatic(string $name, array $arguments = []): static
    {
        $array = self::toArray();

        if (!isset($array[$name])) {
            throw new InvalidKeyException(static::class, $name);
        }

        return new static(reset($array[$name]));
    }
}
