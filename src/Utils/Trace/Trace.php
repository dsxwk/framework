<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Utils\Trace;

use Ramsey\Uuid\Uuid;

class Trace
{
    /**
     * traceId
     *
     * @var string|null
     */
    protected static ?string $traceId = null;

    /**
     * 初始化
     *
     * @param string|null $traceId
     *
     * @return void
     */
    public static function init(?string $traceId = null): void
    {
        static::$traceId = $traceId ?? static::generate();
    }

    /**
     * 获取
     *
     * @return string
     */
    public static function get(): string
    {
        return static::$traceId ??= static::generate();
    }

    /**
     * 生成
     *
     * @return string
     */
    public static function generate(): string
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * 清除
     *
     * @return void
     */
    public static function clear(): void
    {
        static::$traceId = null;
    }
}