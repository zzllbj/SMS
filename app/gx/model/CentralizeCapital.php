<?php
// +----------------------------------------------------------------------
// | admin [ 学无止境 ]
// +----------------------------------------------------------------------
// | Author: zzllbj@126.com
// +----------------------------------------------------------------------
namespace app\gx\model;

use plugin\saiadmin\basic\BaseModel;

/**
 * 资金归集模型
 */
class CentralizeCapital extends BaseModel
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
    protected $table = 'gx_centralize_capital';

    
    /**
     * 课题名称 搜索
     */
    public function searchNameIdAttr($query, $value)
    {
        $query->where('name_id', 'like', '%'.$value.'%');
    }


}
