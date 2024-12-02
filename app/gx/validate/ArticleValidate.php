<?php
// +----------------------------------------------------------------------
// | admin [ 学无止境 ]
// +----------------------------------------------------------------------
// | Author: zzllbj@126.com
// +----------------------------------------------------------------------
namespace app\gx\validate;

use think\Validate;

/**
 * 文章管理验证器
 */
class ArticleValidate extends Validate
{
    /**
     * 定义验证规则
     */
    protected $rule =   [
        'category_id' => 'require',
        'title' => 'require',
        'describe' => 'require',
        'content' => 'require',
    ];

    /**
     * 定义错误信息
     */
    protected $message  =   [
        'category_id' => '分类id必须填写',
        'title' => '文章标题必须填写',
        'describe' => '文章简介必须填写',
        'content' => '文章内容必须填写',
    ];

    /**
     * 定义场景
     */
    protected $scene = [
        'save' => [
            'category_id',
            'title',
            'describe',
            'content',
        ],
        'update' => [
            'category_id',
            'title',
            'describe',
            'content',
        ],
    ];

}
