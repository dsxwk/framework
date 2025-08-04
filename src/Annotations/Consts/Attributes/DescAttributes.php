<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Annotations\Consts\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class DescAttributes
{
    /**
     * 描述属性
     *
     * @param string     $desc  描述
     * @param int        $sort  排序
     * @param int|string $group 分组
     */
    public function __construct(
        public string     $desc = '',
        public int        $sort = 0,
        public int|string $group = ''
    )
    {

    }
}