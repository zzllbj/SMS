<?php
// +----------------------------------------------------------------------
// | admin [ 学无止境 ]
// +----------------------------------------------------------------------
// | Author: zzllbj@126.com
// +----------------------------------------------------------------------
namespace app\gx\validate;

use think\Validate;

/**
 * 成果管理验证器
 */
class ResultValidate extends Validate
{
    /**
     * 定义验证规则
     */
    protected $rule =   [
#foreach($column in $columns)
#if[$is_required == "true" && $is_pk != 2]
        '${column_name}' => 'require',
#/if
#/foreach
    ];

    /**
     * 定义错误信息
     */
    protected $message  =   [
#foreach($column in $columns)
#if[$is_required == "true" && $is_pk != 2]
        '${column_name}' => '${column_comment}必须填写',
#/if
#/foreach
    ];

    /**
     * 定义场景
     */
    protected $scene = [
        'save' => [
#foreach($column in $columns)
#if[$is_required == "true" && $is_pk != 2]
            '${column_name}',
#/if
#/foreach
        ],
        'update' => [
#foreach($column in $columns)
#if[$is_required == "true" && $is_pk != 2]
            '${column_name}',
#/if
#/foreach
        ],
    ];

}
