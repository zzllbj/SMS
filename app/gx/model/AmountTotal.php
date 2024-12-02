<?php
// +----------------------------------------------------------------------
// | admin [ 学无止境 ]
// +----------------------------------------------------------------------
// | Author: zzllbj@126.com
// +----------------------------------------------------------------------
namespace app\gx\model;

use plugin\saiadmin\basic\BaseModel;

/**
 * 预算资金模型
 */
class AmountTotal extends BaseModel
{

    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'id';

    /**
     * 数据库表名称
     * @var string
     */
    protected $table = 'gx_amount_total';


    /**
     * 项目ID保存数组转换
     */
    public function setProjectIdAttr($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

     /**
     * 项目ID读取数组转换
     */
    public function getProjectIdAttr($value)
    {
        return json_decode($value ?? '', true);
    }


}
