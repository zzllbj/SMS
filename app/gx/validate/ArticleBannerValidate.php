<?php
// +----------------------------------------------------------------------
// | admin [ 学无止境 ]
// +----------------------------------------------------------------------
// | Author: zzllbj@126.com
// +----------------------------------------------------------------------
namespace app\gx\validate;

use think\Validate;

/**
 * 文章轮播验证器
 */
class ArticleBannerValidate extends Validate
{
    /**
     * 定义验证规则
     */
    protected $rule =   [
        'banner_type' => 'require',
        'title' => 'require',
    ];

    /**
     * 定义错误信息
     */
    protected $message  =   [
        'banner_type' => '类型必须填写',
        'title' => '标题必须填写',
    ];

    /**
     * 定义场景
     */
    protected $scene = [
        'save' => [
            'banner_type',
            'title',
        ],
        'update' => [
            'banner_type',
            'title',
        ],
    ];

}
