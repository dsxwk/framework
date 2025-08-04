<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Annotations\Consts\Abstracts;

use Exception;
use ReflectionClass;

abstract class GetConstOther
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
     * 解析类或常量的注释
     *
     * @param $reflection
     *
     * @return array
     */
    private static function parseDocComment($reflection): array
    {
        $comment = $reflection->getDocComment();
        $pattern = '/@(\w+)\s*\(\s*[\'"]?([^\'"]+)[\'"]?\s*\)/';
        preg_match_all($pattern, $comment, $matches, PREG_SET_ORDER);
        $result = [];
        foreach ($matches as $match) {
            $result[$match[1]] = $match[2];
        }

        return $result;
    }

    /**
     * 获取类的所有常量
     *
     * @return array
     */
    private static function getConstants(): array
    {
        $reflection = new ReflectionClass(static::class);

        return $reflection->getConstants();
    }

    /**
     * 静态方法调用,用于访问类注释
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @throws Exception
     */
    public static function __callStatic($name, $arguments)
    {
        static::$classAnnotations = static::parseDocComment(new ReflectionClass(static::class));
        if (isset(static::$classAnnotations[$name])) {
            return static::$classAnnotations[$name];
        }
        throw new Exception("Static method $name does not exist.");
    }

    /**
     * 获取常量属性方法
     *
     * @param $value
     *
     * @return array|mixed|null
     */
    public static function getAttributes($value = null): mixed
    {
        foreach (static::getConstants() as $constName => $constValue) {
            static::$constAnnotations[$constValue] = (object)static::parseDocComment(
                (new ReflectionClass(static::class))->getReflectionConstant($constName)
            );
        }

        return $value ? static::$constAnnotations[$value] ?? null : static::$constAnnotations;
    }
}