<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Laravel\Provider;

use Dsxwk\Framework\Laravel\Param\PageDataParam;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class BuilderProvider extends ServiceProvider
{
    public function boot(): void
    {
        Builder::macro(
            'pageData',
            function (array $columns = ['*']): PageDataParam
            {
                $page     = (int)request()->input('page', 1);
                $pageSize = (int)request()->input('pageSize', 10);

                /**
                 * @var Builder $this
                 */
                return new PageDataParam(
                    [
                        'page'     => $page,
                        'pageSize' => $pageSize,
                        'total'    => $this->count(),
                        'list'     => $this->forPage($page, $pageSize)->get($columns)->toArray()
                    ]
                );
            }
        );
    }
}