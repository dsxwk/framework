<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Annotations\Consts\Abstracts;

use ReflectionClass;

abstract class GetConst
{
    /**
     * 用于存储类注释解析结果
     *
     * @var array
     */
    private static array $classAnnotations = [];

    /**
     * 用于存储常量注释解析结果
     *
     * @var array
     */
    private static array $constAnnotations = [];

    /**
     * 获取类的所有常量
     *
     * @return array
     */
    public static function getMap(): array
    {
        $reflection = new ReflectionClass(static::class);

        return $reflection->getConstants();
    }

    /**
     * 获取类的所有常量键
     *
     * @return int[]|string[]
     */
    public static function keys(): array
    {
        $const = static::getMap();

        return array_keys($const);
    }

    /**
     * 获取类的所有常量值
     *
     * @return array
     */
    public static function values(): array
    {
        $const = static::getMap();

        return array_values($const);
    }

    /**
     * 获取类属性方法
     * 
     * @param string $classAttributes
     *
     * @return GetConst|object|null
     */
    public static function getClassAttributes(string $classAttributes): mixed
    {
        $reflection = new ReflectionClass(static::class);
        $attributes = $reflection->getAttributes($classAttributes);

        return $attributes[0]?->newInstance();
    }

    /**
     * 获取常量属性方法
     *
     * @param string|int|null $value
     *
     * @return array|mixed
     */
    public static function getConstAttributes(string|int|null $value = null): mixed
    {
        $constants = static::getMap();
        foreach ($constants as $key => $const) {
            $reflectionConstant = (new ReflectionClass(static::class))->getReflectionConstant($key);
            $attributes = $reflectionConstant->getAttributes();
            static::$constAnnotations[$const] = $attributes[0]?->newInstance();
        }

        return $value ? static::$constAnnotations[$value] : static::$constAnnotations;
    }
}