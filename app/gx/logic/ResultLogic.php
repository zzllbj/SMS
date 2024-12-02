<?php
// +----------------------------------------------------------------------
// | admin [ 学无止境 ]
// +----------------------------------------------------------------------
// | Author: zzllbj@126.com
// +----------------------------------------------------------------------
namespace app\gx\logic;

use plugin\saiadmin\basic\BaseLogic;
use plugin\saiadmin\exception\ApiException;
use plugin\saiadmin\utils\Helper;
use app\gx\model\Result;

/**
 * 成果管理逻辑层
 */
class ResultLogic extends BaseLogic
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new Result();
    }

#whether[$tpl_category == "tree"]
    /**
     * 数据修改
     */
    public function update($data, $where)
    {
        if (!isset($data['${tree_parent_id}'])) {
            $data['${tree_parent_id}'] = 0;
        }
        if ($data['${tree_parent_id}'] == $where['${tree_id}']) {
            throw new ApiException('不能设置父级为自身');
        }
        return $this->model->update($data, $where);
    }

    /**
     * 数据删除
     */
    public function destroy($ids, $force = false)
    {
        $num = $this->model->where('${tree_parent_id}', 'in', $ids)->count();
        if ($num > 0) {
            throw new ApiException('该分类下存在子分类，请先删除子分类');
        } else {
            return $this->model->destroy($ids, $force);
        }
    }

    /**
     * 树形数据
     */
    public function tree($where)
    {
        $query = $this->search($where);
        if (request()->input('tree', 'false') === 'true') {
            $query->field('${tree_id}, ${tree_id} as value, ${tree_name} as label, ${tree_parent_id}');
        }
        $data = $this->getAll($query);
        return Helper::makeTree($data);
    }
#/whether

}
