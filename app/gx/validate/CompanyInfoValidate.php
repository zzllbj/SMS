<?php
// +----------------------------------------------------------------------
// | admin [ 学无止境 ]
// +----------------------------------------------------------------------
// | Author: zzllbj@126.com
// +----------------------------------------------------------------------
namespace app\gx\validate;

use think\Validate;

/**
 * 单位验证器
 */
class CompanyInfoValidate extends Validate
{
    /**
     * 定义验证规则
     */
    protected $rule =   [
        'name' => 'require',
    ];

    /**
     * 定义错误信息
     */
    protected $message  =   [
        'name' => '单位名称必须填写',
    ];

    /**
     * 定义场景
     */
    protected $scene = [
        'save' => [
            'name',
        ],
        'update' => [
            'name',
        ],
    ];

}
