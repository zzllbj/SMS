<?php
// +----------------------------------------------------------------------
// | admin [ 学无止境 ]
// +----------------------------------------------------------------------
// | Author: zzllbj@126.com
// +----------------------------------------------------------------------
namespace app\gx\validate;

use think\Validate;

/**
 * 审核记录表验证器
 */
class AuditRecordValidate extends Validate
{
    /**
     * 定义验证规则
     */
    protected $rule =   [
    ];

    /**
     * 定义错误信息
     */
    protected $message  =   [
    ];

    /**
     * 定义场景
     */
    protected $scene = [
        'save' => [
        ],
        'update' => [
        ],
    ];

}
