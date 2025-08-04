<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Annotations\Enums\Traits;

use Dsxwk\Framework\Annotations\Enums\Attributes\DescAttributes;
use Dsxwk\Framework\Annotations\Enums\Attributes\PrefixAttributes;
use ReflectionEnum;
use ReflectionEnumUnitCase;
use ReflectionClass;
use ReflectionException;
use stdClass;
use ReflectionMethod;

trait GetEnumCase
{
    /**
     * 获取枚举值的实例参数对象
     *
     * @return DescAttributes|null
     * @throws ReflectionException
     */
    private function getDescAttributesInstanceArgs(): ?DescAttributes
    {
        $attributes = (new ReflectionEnumUnitCase(static::class, $this->name))->getAttributes()[0];
        $reflection = new ReflectionClass($attributes->getName());

        return $reflection->newInstanceArgs($attributes->getArguments());
    }

    /**
     * 获取枚举的描述
     *
     * @return string
     * @throws ReflectionException
     */
    public function desc(): string
    {
        return $this->getDescAttributesInstanceArgs()?->desc;
    }

    /**
     * 获取枚举的排序
     *
     * @return int
     * @throws ReflectionException
     */
    public function sort(): int
    {
        return $this->getDescAttributesInstanceArgs()?->sort;
    }

    /**
     * 获取枚举的键的集合
     *
     * @return array
     */
    public static function keys(): array
    {
        return array_map(fn($items) => $items->name, static::cases());
    }

    /**
     * 获取枚举的值的集合
     *
     * @return array
     */
    public static function values(): array
    {
        return array_map(fn($items) => $items->value, static::cases());
    }

    /**
     * 枚举对应的键值对
     *
     * @return array
     */
    public static function map(): array
    {
        return array_combine(static::keys(), static::values());
    }

    /**
     * 枚举值对应的对象
     *
     * @return stdClass
     * @throws ReflectionException
     */
    public static function valuesWithObjects(): stdClass
    {
        $objects    = new stdClass();
        $attributes = static::getObjectsAttributes();
        $values     = static::values();
        foreach ($values as $value) {
            $attrs = [];
            //  ['desc', 'sort', ...]
            foreach ($attributes as $attribute) {
                $data              = static::tryFrom($value)?->$attribute();
                $attrs[$attribute] = $data;
            }
            $objects->$value = $attrs;
        }

        return $objects;
    }

    /**
     * 获取当前对象属性
     *
     * @return array
     * @throws ReflectionException
     */
    private static function getObjectsAttributes(): array
    {
        $name       = static::keys()[0];
        $attributes = (new ReflectionEnumUnitCase(static::class, $name))->getAttributes()[0];
        $reflection = new ReflectionClass($attributes->getName());
        $instance   = $reflection->newInstance();

        return array_keys(json_decode(json_encode($instance), true));
    }

    /**
     * 获取枚举方法集合
     * 
     * @return array
     */
    public function methods(): array
    {
        $reflection = new ReflectionClass(GetEnumCase::class);
        $methods    = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

        $result = [];
        foreach ($methods as $method) {
            $name = $method->getName();
            if ($name !== '__call') {
                $result[] = $name;
            }
        }

        return $result;
    }

    /**
     * 枚举集合排序
     *
     * @param string $order
     *
     * @return array
     * @throws ReflectionException
     */
    public static function sortValues(string $order = 'asc' | 'desc'): array
    {
        $values = static::valuesWithObjects();
        $values = json_decode(json_encode($values), true);
        uasort(
            $values,
            function ($key, $value) use ($order) {
                if ($order === 'asc') {
                    return $key['sort'] <=> $value['sort'];
                }

                return $value['sort'] <=> $key['sort'];
            }
        );

        return $values;
    }

    /**
     * 分组
     *
     * @return int|string|null
     * @throws ReflectionException
     */
    public function group(): int|string|null
    {
        return $this->getDescAttributesInstanceArgs()?->group;
    }

    /**
     * 枚举集合分组
     *
     * @return array
     * @throws ReflectionException
     */
    public static function groupValues(): array
    {
        $result = [];
        foreach (static::valuesWithObjects() as $key => $value) {
            $result[$value['group']][] = $key;
        }

        return $result;
    }

    /**
     * 获取枚举集合分组
     *
     * @param $group
     *
     * @return array
     * @throws ReflectionException
     */
    public static function getGroupValues($group): array
    {
        $groups = static::groupValues();

        return $groups[$group] ?? [];
    }

    /**
     * 获取枚举值的实例参数对象
     *
     * @return PrefixAttributes|null
     */
    private static function getPrefixAttributesInstance(): PrefixAttributes|null
    {
        $reflection = new ReflectionEnum(static::class);
        $attributes = $reflection->getAttributes();
        if (!$attributes) {
            return null;
        }

        return $attributes[0]?->newInstance();
    }

    /**
     * 获取枚举前缀
     *
     * @return string|null
     */
    public static function getPrefix(): string|null
    {
        return static::getPrefixAttributesInstance()?->prefix;
    }

    /**
     * 获取枚举前缀描述
     *
     * @return string|null
     */
    public static function getPrefixDesc(): string|null
    {
        return static::getPrefixAttributesInstance()?->desc;
    }

    /**
     * 获取错误码
     *
     * @return int|null
     */
    public function getErrorCode(): int|null
    {
        if (is_null($this->getPrefix())) {
            return null;
        }

        return (int)($this->getPrefix() . $this->value);
    }

    /**
     * 获取错误码集合
     *
     * @return array
     */
    public static function getErrorCodeValues(): array
    {
        $prefix = static::getPrefix();
        if (is_null($prefix)) {
            return [];
        }

        $values = static::values();
        foreach ($values as &$value) {
            $value = (int)$prefix . $value;
        }

        return $values;
    }

    /**
     * 获取错误码集合对象
     *
     * @return stdClass
     * @throws ReflectionException
     */
    public static function getErrorCodeValuesObject(): stdClass
    {
        $objects    = new stdClass();
        $prefix     = static::getPrefix();
        if (is_null($prefix)) {
            return $objects;
        }
        $attributes = static::getObjectsAttributes();
        $values     = static::values();
        foreach ($values as $value) {
            $attrs = [];
            // ['desc', 'sort', ...]
            foreach ($attributes as $attribute) {
                $data              = static::tryFrom($value)?->$attribute();
                $attrs[$attribute] = $data;
            }
            $objects->{(int)$prefix . $value} = $attrs;
        }

        return $objects;
    }

    /**
     * 获取枚举某个属性的值
     *
     * @param mixed $attribute
     *
     * @return mixed
     * @throws ReflectionException
     */
    public function getEnumAttributeValue(mixed $attribute): mixed
    {
        $attributes = static::getObjectsAttributes();
        foreach ($attributes as $attr) {
            if ($attr == $attribute) {
                return static::tryFrom($this->value)?->$attribute();
            }
        }

        return null;
    }
}