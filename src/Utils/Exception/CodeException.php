<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Utils\Exception;

use Dsxwk\Framework\Annotation\Enums\interface\ErrCodeInterface;
use Dsxwk\Framework\Utils\Query\Handle;
use Dsxwk\Framework\Utils\Trace\Trace;
use Exception;
use Throwable;

class CodeException extends Exception
{
    public       $code    = 500;

    public       $message = '';

    public array $data    = [];

    public int   $status  = 500;

    public function __construct(
        int|ErrCodeInterface $code = 500,
        string               $message = '',
        array                $data = [],
        ?int                 $status = 200,
        Throwable            $previous = null
    )
    {
        if ($code) $this->code = $code;
        if ($message) $this->message = $message;
        if ($code instanceof ErrCodeInterface) {
            $this->code    = $code->getErrCode();
            $this->message = $code->getErrMsg();
        }
        if (config('app.debug')) {
            $data['debug']['traceId'] = Trace::get();
            $data['debug']['mysql']   = Handle::getSqlRecord();
            $data['debug']['redis']   = Handle::getRedisRecord();
            Handle::clear();
        }
        if ($data) $this->data = $data;
        if ($status) $this->status = $status;

        parent::__construct($message, $code, $previous);
    }

    public function getData(): array
    {
        return $this->data;
    }
}