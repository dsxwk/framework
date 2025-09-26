<?php

declare(strict_types=1);

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
            $newKey = is_string($key) ? ($toSnake ? toSnakeCase($key) : toCamelCase($key)) : $key;
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

if (!function_exists('batchUpdateSql')) {
    /**
     * 生成批量更新sql
     *
     * @param       $table      string 数据表
     * @param       $data       array 更新数据
     * @param       $primaryKey string 主键字段
     * @param array $conditions
     *
     * @return false|string
     */
    function batchUpdateSql(string $table = '', array $data = [], string $primaryKey = 'id', array $conditions = []): bool|string
    {
        if (!is_array($data) || !$primaryKey || !is_array($conditions)) return false;
        $sql  = '';
        $keys = array_keys(current($data));
        foreach ($keys as $column) {
            if ($column == $primaryKey) {
                continue;
            }
            $sql .= sprintf('`%s` = CASE `%s` ' . PHP_EOL, $column, $primaryKey);
            foreach ($data as $line) {
                $sql .= sprintf('WHEN \'%s\' THEN \'%s\' ' . PHP_EOL, $line[$primaryKey], $line[$column]);
            }
            $sql .= 'END,';
        }
        $caseWhen = rtrim($sql, ',');

        $where = [];
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $where[] = sprintf('`%s` = \'%s\'', $key, $value);
            }
        }
        $where = !empty($where) ? ' AND ' . implode(' AND ', $where) : '';

        $fields = array_column($data, $primaryKey);
        $fields = implode(
            ',',
            array_map(
                function ($value) {
                    return '\'' . $value . '\'';
                },
                $fields
            )
        );

        return sprintf('UPDATE `%s` SET %s WHERE `%s` IN (%s) %s', $table, $caseWhen, $primaryKey, $fields, $where);
    }
}

if (!function_exists('findDuplicatesWithKeys')) {
    /**
     * 获取数组重复的值与键
     *
     * @param array $array
     *
     * @return array
     */
    function findDuplicatesWithKeys(array $array): array
    {
        $valuesCount = array_count_values($array);
        $duplicates  = [];
        foreach ($valuesCount as $value => $count) {
            if ($count > 1) {
                $keys = array_keys($array, $value);
                foreach ($keys as $key) {
                    $duplicates[$key] = $value;
                }
            }
        }

        return $duplicates;
    }
}

if (!function_exists('buildTree')) {
    /**
     * 构建树形结构
     *
     * @param array  $items       原始数据（必须包含 id 和 pid）
     * @param string $idKey       ID 字段名
     * @param string $parentKey   父 ID 字段名
     * @param string $childrenKey 子节点字段名
     *
     * @return array 树形数组
     */
    function buildTree(array $items, string $idKey = 'id', string $parentKey = 'pid', string $childrenKey = 'children'): array
    {
        $tree = [];
        $map  = [];

        foreach ($items as &$item) {
            $map[$item[$idKey]] = &$item;
            $item[$childrenKey] = [];
        }

        foreach ($items as &$item) {
            if ($item[$parentKey] && isset($map[$item[$parentKey]])) {
                $map[$item[$parentKey]][$childrenKey][] = &$item;
            } else {
                $tree[] = &$item;
            }
        }

        return $tree;
    }
}

if (!function_exists('flattenTree')) {
    /**
     * 展平树形数组
     *
     * @param array  $tree
     * @param string $childrenKey
     *
     * @return array
     */
    function flattenTree(array $tree, string $childrenKey = 'children'): array
    {
        $result = [];
        foreach ($tree as $node) {
            $currentLevel = array_filter(
                $node,
                function ($key) use ($childrenKey) {
                    return $key !== $childrenKey;
                },
                ARRAY_FILTER_USE_KEY
            );

            $result[] = $currentLevel;
            if (isset($node[$childrenKey]) && is_array($node[$childrenKey])) {
                $result = array_merge($result, flattenTree($node[$childrenKey], $childrenKey));
            }
        }

        return $result;
    }
}

if (!function_exists('subtext')) {
    /**
     * 字符串截取并且超出显示省略号
     *
     * @param string $text
     * @param int    $length
     * @param string $flag
     *
     * @return string
     */
    function subtext(string $text, int $length = 50, string $flag = ' ...'): string
    {
        if (mb_strlen($text, 'UTF-8') > $length)
            return mb_substr($text, 0, $length, 'UTF-8') . $flag;

        return $text;
    }
}

if (!function_exists('pregHtml')) {
    /**
     * 正则去除html标签
     *
     * @param string $str
     *
     * @return string
     */
    function pregHtml(string $str): string
    {
        $str = strip_tags($str);

        return html_entity_decode($str, ENT_QUOTES, 'UTF-8');
    }
}