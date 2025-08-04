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
     * @param array  $data
     * @param int    $code
     * @param string $msg
     * @param int    $httpCode
     *
     * @return Response
     */
    function apiResponse(array $data = [], int $code = 0, string $msg = 'success', int $httpCode = 200): Response
    {
        $response = new Response();

        return $response->setCode($code)
            ->setMsg($msg)
            ->setHeader('Content-Type', 'application/json; charset=utf-8')
            ->setBody(json_encode($data, JSON_UNESCAPED_UNICODE))
            ->setStatus($httpCode);
    }
}