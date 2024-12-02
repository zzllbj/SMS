<?php
// +----------------------------------------------------------------------
// | admin [ 学无止境 ]
// +----------------------------------------------------------------------
// | Author: zzllbj@126.com
// +----------------------------------------------------------------------
namespace app\gx\validate;

use think\Validate;

/**
 * 项目信息验证器
 */
class ProjectsInfoValidate extends Validate
{
    /**
     * 定义验证规则
     */
    protected $rule =   [
        'name' => 'require',
        'start_date' => 'require',
        'end_date' => 'require',
    ];

    /**
     * 定义错误信息
     */
    protected $message  =   [
        'name' => '课题名称必须填写',
        'start_date' => '开始时间必须填写',
        'end_date' => '结束时间必须填写',
    ];

    /**
     * 定义场景
     */
    protected $scene = [
        'save' => [
            'name',
            'start_date',
            'end_date',
        ],
        'update' => [
            'name',
            'start_date',
            'end_date',
        ],
    ];

}
