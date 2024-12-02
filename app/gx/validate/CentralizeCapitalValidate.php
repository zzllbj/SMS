<?php
// +----------------------------------------------------------------------
// | admin [ 学无止境 ]
// +----------------------------------------------------------------------
// | Author: zzllbj@126.com
// +----------------------------------------------------------------------
namespace app\gx\validate;

use think\Validate;

/**
 * 资金归集验证器
 */
class CentralizeCapitalValidate extends Validate
{
    /**
     * 定义验证规则
     */
    protected $rule =   [
        'name_id' => 'require',
    ];

    /**
     * 定义错误信息
     */
    protected $message  =   [
        'name_id' => '课题名称必须填写',
    ];

    /**
     * 定义场景
     */
    protected $scene = [
        'save' => [
            'name_id',
        ],
        'update' => [
            'name_id',
        ],
    ];

}
