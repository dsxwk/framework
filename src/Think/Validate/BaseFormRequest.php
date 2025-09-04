<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Think\Validate;

use Exception;
use think\exception\ValidateException;
use think\helper\Arr;
use think\helper\Str;
use think\Validate;

abstract class BaseFormRequest extends Validate
{
    protected      $rule    = [];

    /**
     * 自定义验证场景规则
     *
     * @return array
     */
    protected function sceneRules(): array
    {
        return [];
    }

    /**
     * 验证字段描述
     *
     * @var array
     */
    protected $field = [];

    /**
     * 验证提示信息
     *
     * @var array
     */
    protected $message = [];

    /**
     * 获取当前场景
     *
     * @return string
     */
    protected function getAction(): string
    {
        return '';
    }

    /**
     * 验证场景规则
     *
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->setSceneRules();
    }

    /**
     * 设置验证场景规则
     *
     * @return void
     * @throws Exception
     */
    private function setSceneRules(): void
    {
        if (empty($this->getAction())) throw new Exception('请设置getAction方法');

        $this->rule($this->sceneRules()[$this->getAction()] ?? []);
    }

    /**
     * 获取错误信息
     * @param bool  $withKey 是否包含字段信息
     * @return array|string
     */
    public function getError(bool $withKey = false): array|string
    {
        if ($withKey) {
            return $this->error;
        }
        return empty($this->error) ? '' : array_values($this->error)[0];
    }

    /**
     * 返回经过验证的数据，只包含验证规则中的数据
     * @param array $data
     * @param array|string $rules
     * @return array
     */
    public function checked(array $data, array | string $rules = []): array
    {
        $results  = [];
        $checkRes = $this->check($data, $rules);

        if (!$checkRes) return $results;
        $missingValue = Str::random(10);

        foreach (array_keys($this->getRules()) as $key) {
            if (str_contains($key, '|')) {
                [$key] = explode('|', $key);
            }
            $value = data_get($data, $key, $missingValue);

            if ($value !== $missingValue) {
                Arr::set($results, $key, $value);
            }
        }

        return $results;
    }
}
