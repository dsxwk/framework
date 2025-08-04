<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Annotations\Consts;

use Dsxwk\Framework\Annotations\Consts\Abstracts\GetConst;
use Dsxwk\Framework\Annotations\Consts\Attributes\PrefixAttributes;
use Dsxwk\Framework\Annotations\Consts\Attributes\DescAttributes;

#[PrefixAttributes('10000', '错误码前缀')]
class ErrCodeConst extends GetConst
{
    #[DescAttributes('成功', 1, 200)]
    const SUCCESS = 0;

    #[DescAttributes('参数错误', 2, 400)]
    const PARAM_ERROR = 400;

    #[DescAttributes('请求未授权', 3, 400)]
    const UNAUTHORIZED = 401;

    #[DescAttributes('权限不足', 5, 400)]
    const FORBIDDEN = 403;

    #[DescAttributes('请求方法错误', 2, 400)]
    const METHOD_NOT_ALLOWED = 405;

    #[DescAttributes('服务器内部错误', 6, 500)]
    const INTERNAL_SERVER_ERROR = 500;
}