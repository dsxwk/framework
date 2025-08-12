<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Think\Validate;

use Exception;
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
}
