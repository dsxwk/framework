<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Annotations\Enums;

use Dsxwk\Framework\Annotations\Enums\Attributes\DescAttributes;
use Dsxwk\Framework\Annotations\Enums\Attributes\PrefixAttributes;
use Dsxwk\Framework\Annotations\Enums\interface\ErrCodeInterface;
use Dsxwk\Framework\Annotations\Enums\Traits\GetEnumCase;
use Dsxwk\Framework\Annotations\Enums\Traits\GetErrCode;

#[PrefixAttributes('100', 'API错误码')]
enum ErrCodeEnum: int implements ErrCodeInterface
{
    use GetErrCode;

    #[DescAttributes('success', group: '200')]
    case SUCCESS = 0;

    #[DescAttributes('参数错误', group: '400')]
    case PARAM_ERROR = 400;

    #[DescAttributes('请求未授权', group: '400')]
    case UNAUTHORIZED = 401;

    #[DescAttributes('权限不足', group: '400')]
    case FORBIDDEN = 403;

    #[DescAttributes('请求方法错误', group: '400')]
    case METHOD_NOT_ALLOWED = 405;

    #[DescAttributes('服务器内部错误', group: '500')]
    case INTERNAL_SERVER_ERROR = 500;
}