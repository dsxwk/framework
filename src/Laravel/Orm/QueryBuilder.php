<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Laravel\Orm;

use Illuminate\Database\Eloquent\Builder;

class QueryBuilder extends Builder
{
    /**
     * 分页数据
     *
     * @return array
     */
    public function pageData(): array
    {
        $page     = (int)request()->input('page', 1);
        $pageSize = (int)request()->input('pageSize', 10);
        $total    = $this->count();
        $list     = $this->forPage($page, $pageSize)
            ->get()
            ->toArray();

        return [
            'list'     => $list,
            'total'    => $total,
            'page'     => $page,
            'pageSize' => $pageSize,
        ];
    }

    /**
     * 更新数据(支持驼峰转换)
     *
     * @param array $values
     *
     * @return int
     */
    public function update(array $values = []): int
    {
        if ($this->isCamel()) {
            $values = convertKeysToSnake($values);
        }

        return parent::update($values);
    }

    /**
     * 是否使用驼峰字段
     *
     * @return bool
     */
    private function isCamel(): bool
    {
        return property_exists($this->model, 'isCamel') && $this->model::${'isCamel'} ?? false;
    }
}