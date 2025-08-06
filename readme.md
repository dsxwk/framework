## 常量和枚举

```PHP
<?php

declare(strict_types=1);

use ReflectionException;
use Dsxwk\Framework\Annotations\Enums\ErrCodeEnum;
use Dsxwk\Framework\Annotations\Consts\ErrCodeConst;
use Dsxwk\Framework\Annotations\Consts\ErrCodeConstOther;

class Test
{
    /**
     * 测试
     *
     * @return void
     * @throws ReflectionException
     */
    public function index()
    {
        $desc               = ErrCodeEnum::INTERNAL_SERVER_ERROR->desc();
        $sort               = ErrCodeEnum::INTERNAL_SERVER_ERROR->sort();
        $keys               = ErrCodeEnum::keys();
        $values             = ErrCodeEnum::values();
        $map                = ErrCodeEnum::map();
        $tryFrom            = ErrCodeEnum::tryFrom(500)->desc();
        $valuesWithObjects  = ErrCodeEnum::valuesWithObjects();
        $methods            = ErrCodeEnum::INTERNAL_SERVER_ERROR->methods();
        $sortValues         = ErrCodeEnum::sortValues('asc');
        $group              = ErrCodeEnum::INTERNAL_SERVER_ERROR->group();
        $groupValues        = ErrCodeEnum::groupValues();
        $getGroupValues     = ErrCodeEnum::getGroupValues($group);
        $prefix             = ErrCodeEnum::getPrefix();
        $code               = ErrCodeEnum::INTERNAL_SERVER_ERROR->getErrorCode();
        $codeValues         = ErrCodeEnum::getErrorCodeValues();
        $codeValuesObjects  = ErrCodeEnum::getErrorCodeValuesObject();
        $enumAttributeValue = ErrCodeEnum::INTERNAL_SERVER_ERROR->getEnumAttributeValue('desc');

        $constValue      = ErrCodeConstOther::INTERNAL_SERVER_ERROR;
        $classDesc       = ErrCodeConstOther::desc();
        $classPrefix     = ErrCodeConstOther::prefix();
        $constCode       = ErrCodeConstOther::INTERNAL_SERVER_ERROR;
        $constAttributes = ErrCodeConstOther::getAttributes();
        $constDesc       = ErrCodeConstOther::getAttributes(ErrCodeConstOther::INTERNAL_SERVER_ERROR)->desc;

        $constValue1      = ErrCodeConst::INTERNAL_SERVER_ERROR;
        $constMap         = ErrCodeConst::getMap();
        $constKeys        = ErrCodeConst::keys();
        $constValues      = ErrCodeConst::values();
        $constCode1       = ErrCodeConst::INTERNAL_SERVER_ERROR;
        $classAttributes1 = ErrCodeConst::getClassAttributes(PrefixAttributes::class);
        $constAttributes1 = ErrCodeConst::getConstAttributes();
        $constAttributes2 = ErrCodeConst::getConstAttributes(ErrCodeConst::INTERNAL_SERVER_ERROR);
        $constDesc1       = ErrCodeConst::getAttributes(ErrCodeConst::INTERNAL_SERVER_ERROR)->desc;
    }
}

```

## think 验证器

### 基类
```php
<?php

declare(strict_types=1);

namespace app\request;

use Dsxwk\Framework\ThinkValidate;

abstract class BaseRequest extends BaseFormRequest
{
    /**
     * 获取当前场景
     *
     * @return string
     */
    protected function getAction(): string
    {
        // 在 webman 中使用, 其他框架自行获取当前的请求的控制器方法
        return request()->action;
    }
}
```

### 具体使用
```php
<?php

declare(strict_types=1);

namespace app\request;

class UserRequest extends BaseRequest
{
    /**
     * 自定义验证场景规则
     *
     * @return array
     */
    protected function sceneRules(): array
    {
        return [
            'create' => [
                'username'  => 'require|max:25',
                'password' => 'require|max:16',
            ],
        ];
    }

    /**
     * 验证字段描述
     *
     * @var array
     */
    protected $field   = [
        'username'  => '用户名',
        'password'   => '密码',
    ];

    /**
     * 验证提示信息
     *
     * @var array
     */
    protected $message = [
        'username.require'  => '用户名不能为空',
        'password.max'      => '密码最多不能超过16个字符',
    ];
}
```