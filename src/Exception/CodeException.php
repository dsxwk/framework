<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Exception;

use Dsxwk\Framework\Annotations\Enums\interface\ErrCodeInterface;
use Dsxwk\Framework\QueryRecord\RecordHandle;
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
        if (config('app.debug')) $data['debug']['mysql'] = RecordHandle::getSqlRecord();
        if ($data) $this->data = $data;
        if ($status) $this->status = $status;

        parent::__construct($message, $code, $previous);
    }

    public function getData(): array
    {
        return $this->data;
    }
}