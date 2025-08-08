<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Param;

abstract class BaseParam
{
    /**
     * 构造函数
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (isset($value) && property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return void
     */
    public function __call(string $name, array $arguments)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }
    }

    public function __toString()
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}