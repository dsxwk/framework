<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Webman\Redis;

use Dsxwk\Framework\QueryRecord\RecordHandle;
use support\Redis as SupportRedis;

abstract class Predis extends SupportRedis
{
    public static function __callStatic(string $name, array $arguments)
    {
        $start    = microtime(true);
        $result   = static::connection()->{$name}(... $arguments);
        $duration = round((microtime(true) - $start) * 1000, 2);

        $redisRecord = [
            'call'     => "$name(" . json_encode($arguments) . ")",
            'duration' => $duration . ' ms',
            'result'   => $result
        ];
        RecordHandle::setRedisRecord($redisRecord);

        return $result;
    }
}