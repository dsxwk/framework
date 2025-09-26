<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Utils\Param;

if (PHP_VERSION_ID >= 80200) {
    #[\AllowDynamicProperties]
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

        public function __set(string $name, $value)
        {
            $this->{$name} = $value;
        }

        public function toArray(bool $isCamel = false): array
        {
            $data = get_object_vars($this);
            if ($isCamel) {
                $data = keysToCamelOrSnake($data, false);
            } else {
                $data = keysToCamelOrSnake($data);
            }

            return $data;
        }
    }
} else {
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

        public function __set(string $name, $value)
        {
            $this->{$name} = $value;
        }

        public function toArray(bool $isCamel = false): array
        {
            $data = get_object_vars($this);
            if ($isCamel) {
                $data = keysToCamelOrSnake($data, false);
            } else {
                $data = keysToCamelOrSnake($data);
            }

            return $data;
        }
    }
}