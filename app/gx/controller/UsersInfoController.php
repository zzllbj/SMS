<?php
// +----------------------------------------------------------------------
// | admin [ 学无止境 ]
// +----------------------------------------------------------------------
// | Author: zzllbj@126.com
// +----------------------------------------------------------------------
namespace app\gx\controller;

use app\gx\logic\UsersInfoLogic;
use app\gx\validate\UsersInfoValidate;
use hg\apidoc\annotation as Apidoc;
use plugin\saiadmin\basic\BaseController;
use support\Cache;
use support\Request;
use support\Response;

/**
 * @Apidoc\Title("人员信息")
 */
class UsersInfoController extends BaseController
{

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->logic    = new UsersInfoLogic();
        $this->validate = new UsersInfoValidate;
        parent::__construct();
    }

    /**
     * @Apidoc\Title("数据列表")
     * @Apidoc\Url("/gx/UsersInfo/index")
     * @Apidoc\Method("GET")
     * @Apidoc\Query("page", type="int", require=false, desc="框架自带-页码,默认1", default="1")
     * @Apidoc\Query("limit", type="int", require=false, desc="框架自带-每页数据,默认10", default="10")
     * @Apidoc\Query("saiType", type="string", require=false, desc="框架自带-获取数据类型;默认list分页;all全部数据", default="list")
     * @Apidoc\Query("orderBy", type="string", require=false, desc="框架自带-排序字段,默认主键", default="")
     * @Apidoc\Query("orderType", type="string", require=false, desc="框架自带-排序方式,默认ASC", default="")
     * @Apidoc\Query("name", type="varchar", require=false, desc="姓名", default="")
     * @Apidoc\Query("jobtitle", type="int", require=false, desc="职称", default="")
     * @Apidoc\Query("type", type="int", require=false, desc="类型", default="")
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
        $name      = $request->input('name', '');
        $jobtitle  = $request->input('jobtitle', '');
        $type      = $request->input('type', '');

        $cacheKeyParams = [
            'page'      => $page,
            'limit'     => $limit,
            'saiType'   => $saiType,
            'orderBy'   => $orderBy,
            'orderType' => $orderType,
            'name'      => $name,
            'jobtitle'  => $jobtitle,
            'type'      => $type,
        ];

        $rediskey = 'usersinfo:' . md5(json_encode($cacheKeyParams));

        $data = Cache::get($rediskey);
        if ($data) {
            echo '缓存命中：' . $rediskey . "\n";
            return $this->success($data);
        }

        $where = $request->more([
            ['name', ''],
            ['jobtitle', ''],
            ['type', ''],
        ]);
        $query = $this->logic->search($where);
        $userlistdata  = $this->logic->getList($query);
        $data = $this->logic->computerage($userlistdata);
        Cache::set($rediskey, $data, 3600);
        return $this->success($data);
    }

    /**
     * @Apidoc\Title("保存数据")
     * @Apidoc\Url("/gx/UsersInfo/save")
     * @Apidoc\Method("POST")
     * @Apidoc\Query("name", type="varchar", require=false, desc="姓名", default="")
     * @Apidoc\Query("sex", type="int", require=false, desc="性别", default="")
     * @Apidoc\Query("tel", type="varchar", require=false, desc="电话", default="")
     * @Apidoc\Query("cert", type="varchar", require=false, desc="证书", default="")
     * @Apidoc\Query("professional", type="int", require=false, desc="专业", default="")
     * @Apidoc\Query("jobtitle", type="int", require=false, desc="职称", default="")
     * @Apidoc\Query("educated", type="int", require=false, desc="学历", default="")
     * @Apidoc\Query("type", type="int", require=false, desc="类型", default="")
     * @Apidoc\Query("department", type="int", require=false, desc="部门", default="")
     * @Apidoc\Query("born_date", type="date", require=false, desc="破壳日", default="")
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
            return $this->success('操作成功,已更新缓存');
        } else {
            return $this->fail('操作失败');
        }
    }

    /**
     * @Apidoc\Title("修改数据")
     * @Apidoc\Url("/gx/UsersInfo/update")
     * @Apidoc\Method("PUT")
     * @Apidoc\Query("id", type="int", require=true, desc="主键", default="")
     * @Apidoc\Query("name", type="varchar", require=false, desc="姓名", default="")
     * @Apidoc\Query("sex", type="int", require=false, desc="性别", default="")
     * @Apidoc\Query("tel", type="varchar", require=false, desc="电话", default="")
     * @Apidoc\Query("cert", type="varchar", require=false, desc="证书", default="")
     * @Apidoc\Query("professional", type="int", require=false, desc="专业", default="")
     * @Apidoc\Query("jobtitle", type="int", require=false, desc="职称", default="")
     * @Apidoc\Query("educated", type="int", require=false, desc="学历", default="")
     * @Apidoc\Query("type", type="int", require=false, desc="类型", default="")
     * @Apidoc\Query("department", type="int", require=false, desc="部门", default="")
     * @Apidoc\Query("born_date", type="date", require=false, desc="破壳日", default="")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function update(Request $request, $id): Response
    {
        $id   = $request->input('id', $id);
        $data = $request->post();
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
        if ($result) {
            Cache::clear();
            return $this->success('操作成功,已更新缓存');
        } else {
            return $this->fail('操作失败');
        }
    }

    /**
     * @Apidoc\Title("读取数据")
     * @Apidoc\Url("/gx/UsersInfo/read")
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
            return $this->success($data);
        } else {
            return $this->fail('未查找到信息');
        }
    }

    /**
     * @Apidoc\Title("修改状态")
     * @Apidoc\Url("/gx/UsersInfo/changeStatus")
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
        //Cache::clear();
        if ($result) {
            Cache::clear();
            return $this->success('操作成功,已更新缓存');
        } else {
            return $this->fail('操作失败');
        }
    }

    /**
     * @Apidoc\Title("删除数据")
     * @Apidoc\Url("/gx/UsersInfo/destroy")
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
            //Cache::delete('cache-usersinfo');
            Cache::clear();
            return $this->success('操作成功,已更新缓存');
        } else {
            return $this->fail('参数错误，请检查');
        }
    }

    /**
     * @Apidoc\Title("回收站数据")
     * @Apidoc\Url("/gx/UsersInfo/recycle")
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
        //Cache::delete('cache-usersinfo');
        Cache::clear();
        return $this->success($data);
    }

    /**
     * @Apidoc\Title("恢复数据")
     * @Apidoc\Url("/gx/UsersInfo/recovery")
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
            //Cache::delete('cache-usersinfo');
            Cache::clear();
            return $this->success('恢复成功,已更新缓存');
        } else {
            return $this->fail('参数错误，请检查');
        }
    }

    /**
     * @Apidoc\Title("销毁数据")
     * @Apidoc\Url("/gx/UsersInfo/realDestroy")
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
            //Cache::delete('cache-usersinfo');
            Cache::clear();
            return $this->success('操作成功,已更新缓存');
        } else {
            return $this->fail('参数错误，请检查');
        }
    }

    /**
     * 下载导入模板
     * @return Response
     */
    public function downloadTemplate() : Response
    {
        $file_name = "科技人员.xls";
        return downloadFile($file_name);
    }

    /**
     * @Apidoc\Title("导入数据")
     * @Apidoc\Url("/gx/UsersInfo/import")
     * @Apidoc\Method("POST")
     * @param Request $request
     * @return Response
     */
    public function import(Request $request) : Response
    {
        $file = current($request->file());
        if (!$file || !$file->isValid()) {
            return $this->fail('未找到上传文件');
        }
        $this->logic->import($file);
        return $this->success('导入成功');
    }

    /**
     * @Apidoc\Title("导出数据")
     * @Apidoc\Url("/gx/UsersInfo/export")
     * @Apidoc\Method("POST")
     * @param Request $request
     * @return Response
     */
    public function export(Request $request) : Response
    {
        $where = $request->more([
            ['name', ''],
            ['sex', ''],
            ['tel', ''],
            ['born_date', ''],
        ]);
        return $this->logic->export($where);
    }



}
