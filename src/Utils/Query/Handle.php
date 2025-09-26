<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Utils\Query;

class Handle
{
    protected static array $sqlRecord   = [];

    protected static array $redisRecord = [];

    protected static array $httpRecord  = [];

    public static function setSqlRecord(array $sql = []): void
    {
        static::$sqlRecord[] = $sql;
    }

    public static function setRedisRecord(array $redis = []): void
    {
        static::$redisRecord[] = $redis;
    }

    public static function setHttpRecord(array $http = []): void
    {
        static::$httpRecord[] = $http;
    }

    public static function getSqlRecord(): array
    {
        return static::$sqlRecord;
    }

    public static function getRedisRecord(): array
    {
        return static::$redisRecord;
    }

    public static function getHttpRecord(): array
    {
        return static::$httpRecord;
    }

    public static function all(): array
    {
        return [
            'sql'   => static::$sqlRecord,
            'redis' => static::$redisRecord,
            'http'  => static::$httpRecord,
        ];
    }

    public static function clear(): void
    {
        static::$sqlRecord   = [];
        static::$redisRecord = [];
        static::$httpRecord  = [];
    }
}