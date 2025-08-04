<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Annotations\Enums\Interface;

interface ErrCodeInterface
{
    /**
     * 获取错误码
     *
     * @return int
     */
    public function getErrCode(): int;

    /**
     * 获取错误信息
     *
     * @return string
     */
    public function getErrMsg(): string;
}