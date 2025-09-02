<?php

declare(strict_types=1);

namespace Dsxwk\FrameHelpers\Laravel\Model\Trait;

use Dsxwk\FrameHelpers\Laravel\Orm\QueryBuilder;

trait ModelHelper
{
    /**
     * 是否使用驼峰字段
     *
     * @return bool
     */
    private function isCamel(): bool
    {
        return static::$isCamel ?? false;
    }

    /**
     * @param $query
     *
     * @return QueryBuilder
     */
    public function newEloquentBuilder($query): QueryBuilder
    {
        return new QueryBuilder($query);
    }
}
