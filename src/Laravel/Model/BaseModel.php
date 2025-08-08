<?php

declare(strict_types=1);

namespace Dsxwk\Framework\Laravel\Model;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    /**
     * 重定义主键，默认是id
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 否自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 创建时间字段名
     *
     * @var string
     */
    const CREATED_AT = 'created_at';

    /**
     * 更新时间字段名
     *
     * @var string
     */
    const UPDATED_AT = 'updated_at';

    /**
     * 删除时间字段名
     *
     * @var string
     */
    const DELETED_AT = 'deleted_at';
}