<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Annotations\Consts;

use Attribute;
use Dsxwk\Framework\Annotations\Consts\Abstracts\GetConstOther;

/**
 * @desc('测试描述信息')
 * @prefix('测试前缀')
 * @method static desc
 * @method static prefix
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ErrCodeConstOther extends GetConstOther
{
    /**
     * @desc('成功')
     * @group('200')
     * @sort(1)
     */
    const SUCCESS = 0;

    /**
     * @desc('参数错误')
     * @group('400')
     * @sort(2)
     */
    const PARAM_ERROR = 400;

    /**
     * @desc('请求未授权')
     * @group('400')
     * @sort(3)
     */
    const UNAUTHORIZED = 401;

    /**
     * @desc('权限不足')
     * @group('400')
     * @sort(5)
     */
    const FORBIDDEN = 403;

    /**
     * @desc('请求方法错误')
     * @group('400')
     * @sort(2)
     */
    const METHOD_NOT_ALLOWED = 405;

    /**
     * @desc('服务器内部错误')
     * @group('500')
     * @sort(6)
     */
    const INTERNAL_SERVER_ERROR = 500;
}