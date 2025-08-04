<?php

declare(strict_types=1);

namespace Dsxwk\Framework;

use Dsxwk\Framework\Annotations\Consts\ErrCodeConst;
use Dsxwk\Framework\Http\Response;

if (!function_exists('dd')) {
    /**
     * 打印
     *
     * @param ...$args
     *
     * @return void
     */
    function dd(...$args)
    {
        var_dump(... $args);
        exit;
    }
}

if (!function_exists('apiResponse')) {
    /**
     * 公共响应返回
     *
     * @param mixed  $data
     * @param int    $code
     * @param string $msg
     * @param int    $httpCode
     *
     * @return Response
     */
    function apiResponse(mixed $data = [], int $code = 0, string $msg = 'success', int $httpCode = 200): Response
    {
        if (is_array($data)) $data = json_encode($data, JSON_UNESCAPED_UNICODE);

        $response = new Response();

        return $response->setCode($code)
            ->setMsg($msg)
            ->setHeader('Content-Type', 'application/json; charset=utf-8')
            ->setBody($data)
            ->setStatus($httpCode);
    }
}

if (!function_exists('json')) {
    /**
     * json响应返回
     *
     * @param mixed $data
     * @param int   $flag
     *
     * @return Response
     */
    function json(mixed $data = [], int $flag = JSON_UNESCAPED_UNICODE): Response
    {
        if (is_array($data)) $data = json_encode($data, $flag);

        $response = new Response();

        return $response->setHeader('Content-Type', 'application/json; charset=utf-8')
            ->setBody($data)
            ->setStatus(200);
    }
}
