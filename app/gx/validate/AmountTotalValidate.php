<?php
// +----------------------------------------------------------------------
// | admin [ 学无止境 ]
// +----------------------------------------------------------------------
// | Author: zzllbj@126.com
// +----------------------------------------------------------------------
namespace app\gx\validate;

use think\Validate;

/**
 * 预算资金验证器
 */
class AmountTotalValidate extends Validate
{
    /**
     * 定义验证规则
     */
    protected $rule =   [
        'budget_cost_sum' => 'require',
    ];

    /**
     * 定义错误信息
     */
    protected $message  =   [
        'budget_cost_sum' => '预算总费用必须填写',
    ];

    /**
     * 定义场景
     */
    protected $scene = [
        'save' => [
            'budget_cost_sum',
        ],
        'update' => [
            'budget_cost_sum',
        ],
    ];

}
