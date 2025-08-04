<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Annotations\Consts\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class PrefixAttributes
{
    /**
     * 前缀属性
     *
     * @param string $prefix 前缀
     * @param string $desc   描述
     */
    public function __construct(
        public string $prefix = '',
        public string $desc = ''
    )
    {

    }
}