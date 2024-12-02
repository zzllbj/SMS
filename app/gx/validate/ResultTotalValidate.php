<?php
// +----------------------------------------------------------------------
// | saiadmin [ saiadmin快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: your name
// +----------------------------------------------------------------------
namespace app\gx\validate;

use think\Validate;

/**
 * 成果管理验证器
 */
class ResultTotalValidate extends Validate
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
        'name' => '成果名称必须填写',
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
