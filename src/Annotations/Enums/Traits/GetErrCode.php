<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Annotations\Enums\Traits;

use ReflectionException;

trait GetErrCode
{
    use GetEnumCase;

    /**
     * 获取错误码
     *
     * @return int
     */
    public function getErrCode(): int
    {
        return $this->getErrorCode();
    }

    /**
     * 获取错误信息
     *
     * @return string
     * @throws ReflectionException
     */
    public function getErrMsg(): string
    {
        return $this->desc();
    }
}