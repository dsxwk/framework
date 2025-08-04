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

        $constValue1  = ErrCodeConst::INTERNAL_SERVER_ERROR;
        $constMap   = ErrCodeConst::getMap();
        $constKeys   = ErrCodeConst::keys();
        $constValues = ErrCodeConst::values();
        $constCode1   = ErrCodeConst::INTERNAL_SERVER_ERROR;
        $classAttributes1  = ErrCodeConst::getClassAttributes(PrefixAttributes::class);
        $constAttributes1   = ErrCodeConst::getConstAttributes();
        $constAttributes2   = ErrCodeConst::getConstAttributes(ErrCodeConst::INTERNAL_SERVER_ERROR);
        $constDesc1   = ErrCodeConst::getAttributes(ErrCodeConst::INTERNAL_SERVER_ERROR)->desc;
    }
}

```