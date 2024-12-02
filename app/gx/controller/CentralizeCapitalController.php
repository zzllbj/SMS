<?php
// +----------------------------------------------------------------------
// | admin [ 学无止境 ]
// +----------------------------------------------------------------------
// | Author: zzllbj@126.com
// +----------------------------------------------------------------------
namespace app\gx\controller;

use app\gx\logic\CentralizeCapitalLogic;
use app\gx\validate\CentralizeCapitalValidate;
use hg\apidoc\annotation as Apidoc;
use plugin\saiadmin\basic\BaseController;
use support\Cache;
use support\Request;
use support\Response;

/**
 * @Apidoc\Title("资金归集")
 */
class CentralizeCapitalController extends BaseController
{

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->logic    = new CentralizeCapitalLogic();
        $this->validate = new CentralizeCapitalValidate;
        parent::__construct();
    }

    /**
     * @Apidoc\Title("数据列表")
     * @Apidoc\Url("/gx/CentralizeCapital/index")
     * @Apidoc\Method("GET")
     * @Apidoc\Query("page", type="int", require=false, desc="框架自带-页码,默认1", default="1")
     * @Apidoc\Query("limit", type="int", require=false, desc="框架自带-每页数据,默认10", default="10")
     * @Apidoc\Query("saiType", type="string", require=false, desc="框架自带-获取数据类型;默认list分页;all全部数据", default="list")
     * @Apidoc\Query("orderBy", type="string", require=false, desc="框架自带-排序字段,默认主键", default="")
     * @Apidoc\Query("orderType", type="string", require=false, desc="框架自带-排序方式,默认ASC", default="")
     * @Apidoc\Query("name_id", type="int", require=false, desc="课题名称", default="")
     * @Apidoc\Returned("data", type="array", require=true, desc="分页数据")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $page      = $request->input('page', 1);
        $limit     = $request->input('limit', 10);
        $saiType   = $request->input('saiType', 'list');
        $orderBy   = $request->input('orderBy', '');
        $orderType = $request->input('orderType', '');
        $name_id   = $request->input('name_id', '');

        $cacheKeyParams = [
            'page'      => $page,
            'limit'     => $limit,
            'saiType'   => $saiType,
            'orderBy'   => $orderBy,
            'orderType' => $orderType,
            'name_id'   => $name_id,
        ];

        $rediskey = 'centralize:' . md5(json_encode($cacheKeyParams));

        $data = Cache::get($rediskey);
        if ($data) {
            echo '缓存命中：' . $rediskey . "\n";
            return $this->success($data);
        }

        $where = $request->more([
            ['name_id', ''],
        ]);
        $query = $this->logic->search($where);
        $data  = $this->logic->getList($query);
        //增加千分符
        foreach ($data['data'] as &$record) {
            $record['user_fee']     = number_format($record['user_fee'], 2);
            $record['material_fee'] = number_format($record['material_fee'], 2);
            $record['other_fee']    = number_format($record['other_fee'], 2);
        }
        Cache::set($rediskey, $data, 3600);
        return $this->success($data);
    }

    /**
     * @Apidoc\Title("保存数据")
     * @Apidoc\Url("/gx/CentralizeCapital/save")
     * @Apidoc\Method("POST")
     * @Apidoc\Query("name_id", type="int", require=false, desc="课题名称", default="")
     * @Apidoc\Query("centralize_date", type="date", require=false, desc="归集时间", default="")
     * @Apidoc\Query("user_fee", type="decimal", require=false, desc="人员费", default="")
     * @Apidoc\Query("material_fee", type="decimal", require=false, desc="材料费", default="")
     * @Apidoc\Query("other_fee", type="decimal", require=false, desc="其它费", default="")
     * @Apidoc\Query("audit_status", type="enum", require=false, desc="审核状态", default="")
     * @Apidoc\Query("record_user", type="int", require=false, desc="记录人", default="")
     * @param Request $request
     * @return Response
     */
    public function save(Request $request): Response
    {
        $data = $request->post();
        if ($this->validate) {
            if (! $this->validate->scene('save')->check($data)) {
                return $this->fail($this->validate->getError());
            }
        }
        $result = $this->logic->save($data);
        if ($result) {
            Cache::clear();
            return $this->success('操作成功');
        } else {
            return $this->fail('操作失败');
        }
    }

    /**
     * @Apidoc\Title("修改数据")
     * @Apidoc\Url("/gx/CentralizeCapital/update")
     * @Apidoc\Method("PUT")
     * @Apidoc\Query("id", type="int", require=true, desc="主键", default="")
     * @Apidoc\Query("name_id", type="int", require=false, desc="课题名称", default="")
     * @Apidoc\Query("centralize_date", type="date", require=false, desc="归集时间", default="")
     * @Apidoc\Query("user_fee", type="decimal", require=false, desc="人员费", default="")
     * @Apidoc\Query("material_fee", type="decimal", require=false, desc="材料费", default="")
     * @Apidoc\Query("other_fee", type="decimal", require=false, desc="其它费", default="")
     * @Apidoc\Query("audit_status", type="enum", require=false, desc="审核状态", default="")
     * @Apidoc\Query("record_user", type="int", require=false, desc="记录人", default="")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function update(Request $request, $id): Response
    {
        $id   = $request->input('id', $id);
        $data = $request->post();

        // 定义带有千分符的字段列表
        $moddata = ['user_fee', 'material_fee', 'other_fee'];

        // 遍历数据，处理带千分符的字段
        foreach ($moddata as $field) {
            if (isset($data[$field]) && is_string($data[$field]) && strpos($data[$field], ',') !== false) {
                $data[$field] = (float) str_replace(',', '', $data[$field]);
            }
        }

        if ($this->validate) {
            if (! $this->validate->scene('update')->check($data)) {
                return $this->fail($this->validate->getError());
            }
        }
        $info = $this->logic->find($id);
        if (! $info) {
            return $this->fail('没有找到该数据');
        }
        $result = $this->logic->update($data, [$this->pk => $id]);
        Cache::clear();
        if ($result) {
            return $this->success('操作成功');
        } else {
            return $this->fail('操作失败');
        }
    }

    /**
     * @Apidoc\Title("读取数据")
     * @Apidoc\Url("/gx/CentralizeCapital/read")
     * @Apidoc\Method("GET")
     * @Apidoc\Query("id", type="int", require=true, desc="主键", default="")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function read(Request $request, $id): Response
    {
        $id    = $request->input('id', $id);
        $model = $this->logic->find($id);
        if ($model) {
            $data = is_array($model) ? $model : $model->toArray();
            Cache::clear();
            return $this->success($data);
        } else {
            return $this->fail('未查找到信息');
        }
    }

    /**
     * @Apidoc\Title("修改状态")
     * @Apidoc\Url("/gx/CentralizeCapital/changeStatus")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("id", type="int", require=true, desc="主键", default="")
     * @Apidoc\Param("status", type="int", require=true, desc="状态", default="1")
     * @param Request $request
     * @return Response
     */
    public function changeStatus(Request $request): Response
    {
        $id     = $request->input('id', '');
        $status = $request->input('status', 1);
        $result = $this->logic->where($this->pk, $id)->update(['status' => $status]);
        Cache::clear();
        if ($result) {
            return $this->success('操作成功');
        } else {
            return $this->fail('操作失败');
        }
    }

    /**
     * @Apidoc\Title("删除数据")
     * @Apidoc\Url("/gx/CentralizeCapital/destroy")
     * @Apidoc\Method("DELETE")
     * @Apidoc\Param("ids", type="string|array", require=true, desc="主键", default="")
     * @param Request $request
     * @return Response
     */
    public function destroy(Request $request): Response
    {
        $ids = $request->input('ids', '');
        if (! empty($ids)) {
            $this->logic->destroy($ids);
            Cache::clear();
            return $this->success('操作成功');
        } else {
            return $this->fail('参数错误，请检查');
        }
    }

    /**
     * @Apidoc\Title("回收站数据")
     * @Apidoc\Url("/gx/CentralizeCapital/recycle")
     * @Apidoc\Method("GET")
     * @Apidoc\Query("page", type="int", require=false, desc="框架自带-页码,默认1", default="1")
     * @Apidoc\Query("limit", type="int", require=false, desc="框架自带-每页数据,默认10", default="10")
     * @Apidoc\Query("saiType", type="string", require=false, desc="框架自带-获取数据类型;默认list分页;all全部数据", default="list")
     * @Apidoc\Query("orderBy", type="string", require=false, desc="框架自带-排序字段,默认主键", default="")
     * @Apidoc\Query("orderType", type="string", require=false, desc="框架自带-排序方式,默认ASC", default="")
     * @param Request $request
     * @return Response
     */
    public function recycle(Request $request): Response
    {
        $where = $request->more([
            ['create_time', ''],
        ]);
        $query = $this->logic->recycle()->search($where);
        $data  = $this->logic->getList($query);
        Cache::clear();
        return $this->success($data);
    }

    /**
     * @Apidoc\Title("恢复数据")
     * @Apidoc\Url("/gx/CentralizeCapital/recovery")
     * @Apidoc\Method("POST")
     * @Apidoc\Param("ids", type="string|array", require=true, desc="主键", default="")
     * @param Request $request
     * @return Response
     */
    public function recovery(Request $request): Response
    {
        $ids = $request->input('ids', '');
        if (! empty($ids)) {
            $this->logic->restore($ids);
            Cache::clear();
            return $this->success('恢复成功');
        } else {
            return $this->fail('参数错误，请检查');
        }
    }

    /**
     * @Apidoc\Title("销毁数据")
     * @Apidoc\Url("/gx/CentralizeCapital/realDestroy")
     * @Apidoc\Method("DELETE")
     * @Apidoc\Param("ids", type="string|array", require=true, desc="主键", default="")
     * @param Request $request
     * @return Response
     */
    public function realDestroy(Request $request): Response
    {
        $ids = $request->input('ids', '');
        if (! empty($ids)) {
            $this->logic->destroy($ids, true);
            Cache::clear();
            return $this->success('操作成功');
        } else {
            return $this->fail('参数错误，请检查');
        }
    }

}
