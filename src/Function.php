<?php

declare(strict_types=1);

namespace Dsxwk\Framework;

if (!function_exists('dd')) {
    /**
     * 打印
     *
     * @param ...$args
     *
     * @return void
     */
    function dd(... $args)
    {
        var_dump(... $args);
        exit;
    }
}