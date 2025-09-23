<?php

declare(strict_types=1);

use Illuminate\Support\Str;

// 公共函数
if (!function_exists('keysToCamelOrSnake')) {
    /**
     * 递归将数组键名转换为驼峰命名或者下划线命名
     *
     * @param array $data
     * @param bool  $toSnake
     *
     * @return array
     */
    function keysToCamelOrSnake(array $data, bool $toSnake = true): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            $newKey = is_string($key) ? ($toSnake ? Str::snake($key) : Str::camel($key)) : $key;
            if (is_array($value)) $result[$newKey] = keysToCamelOrSnake($value, $toSnake);
            else $result[$newKey] = $value;
        }

        return $result;
    }
}

if (!function_exists('toCamelCase')) {
    /**
     * 将字符串转换为驼峰命名
     *
     * @param string $str
     * @param bool   $ucfirst
     *
     * @return string
     */
    function toCamelCase(string $str, bool $ucfirst = false): string
    {
        $str = strtolower($str);
        $str = preg_replace_callback(
            '/_([a-z])/',
            function ($matches) {
                return strtoupper($matches[1]);
            },
            $str
        );

        return $ucfirst ? ucfirst($str) : $str;
    }
}

if (!function_exists('toSnakeCase')) {
    /**
     * 将字符串转换为下划线命名
     *
     * @param string $str
     *
     * @return string
     */
    function toSnakeCase(string $str): string
    {
        $str = preg_replace('/([A-Z])/', '_$1', $str);
        $str = strtolower($str);

        return ltrim($str, '_');
    }
}