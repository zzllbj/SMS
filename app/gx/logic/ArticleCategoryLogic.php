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
use app\gx\model\ArticleCategory;

/**
 * 文章分类逻辑层
 */
class ArticleCategoryLogic extends BaseLogic
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new ArticleCategory();
    }

    /**
     * 数据修改
     */
    public function update($data, $where)
    {
        if (!isset($data['parent_id'])) {
            $data['parent_id'] = 0;
        }
        if ($data['parent_id'] == $where['id']) {
            throw new ApiException('不能设置父级为自身');
        }
        return $this->model->update($data, $where);
    }

    /**
     * 数据删除
     */
    public function destroy($ids, $force = false)
    {
        $num = $this->model->where('parent_id', 'in', $ids)->count();
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
            $query->field('id, id as value, category_name as label, parent_id');
        }
        $data = $this->getAll($query);
        return Helper::makeTree($data);
    }

}
