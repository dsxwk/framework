<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Laravel\Orm;

use Dsxwk\Framework\QueryRecord\RecordHandle;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\Capsule\Manager as Capsule;

class Db
{
    /**
     * 初始化
     *
     * @return void
     */
    public static function init(): void
    {
        $capsule = new Capsule;
        $capsule->addConnection(config('database.connections.' . config('database.default', 'mysql')) ?? []);
        $capsule->setEventDispatcher(new Dispatcher(new Container()));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $capsule->getEventDispatcher()->listen(
            QueryExecuted::class,
            function (QueryExecuted $query) {
                $sql      = $query->sql;
                $bindings = $query->bindings;
                $time     = $query->time . ' ms';

                $fullSql   = vsprintf(str_replace("?", "'%s'", $sql), $bindings);
                $sqlRecord = ['sql' => $fullSql, 'time' => $time];
                RecordHandle::setSqlRecord($sqlRecord);
            }
        );
    }
}