<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Http;

use Stringable;

class Handle implements Stringable
{
    public function __construct(
        protected int    $code = 0,
        protected string $msg = '',
        protected string $body = '',
        protected int    $status = 200,
        protected array  $headers = [],
    ) {}

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setMsg(string $msg): self
    {
        $this->msg = $msg;

        return $this;
    }

    public function getMsg(): string
    {
        return $this->msg;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;

        return $this;
    }

    public function getHeader(string $name)
    {
        return $this->headers[$name] ?? null;
    }

    public function setHeaders(string $name, string $value): self
    {
        $this->headers[$name] = $value;

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function __toString(): string
    {
        return $this->body;
    }
}