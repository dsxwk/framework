<?php

declare(strict_types=1);

// 公共函数
if (!function_exists('convertKeysToCamel')) {
    /**
     * 递归将数组键名转换为驼峰命名
     *
     * @param array $data
     *
     * @return array
     */
    function convertKeysToCamel(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            $newKey = is_string($key) ? Str::camel($key) : $key;
            if (is_array($value)) {
                $result[$newKey] = convertKeysToCamel($value);
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }
}

if (!function_exists('convertKeysToSnake')) {
    /**
     * 递归将数组键名转换为下划线命名
     *
     * @param array $data
     *
     * @return array
     */
    function convertKeysToSnake(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            $newKey = is_string($key) ? Str::snake($key) : $key;
            if (is_array($value)) {
                $result[$newKey] = convertKeysToSnake($value);
            } else {
                $result[$newKey] = $value;
            }
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
        $str = preg_replace_callback('/_([a-z])/', function ($matches) {
            return strtoupper($matches[1]);
        }, $str);
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