<?php
// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace plugin\saiadmin\basic;

use plugin\saiadmin\app\logic\system\SystemMenuLogic;
use plugin\saiadmin\app\logic\system\SystemRoleLogic;
use plugin\saiadmin\app\logic\system\SystemUserLogic;
use plugin\saiadmin\exception\ApiException;
use plugin\saiadmin\utils\Arr;
use support\Request;
use support\Response;

/**
 * 基类 控制器继承此类
 */
class BaseController
{

    /**
     * 当前登陆管理员信息
     */
    protected $adminInfo;

    /**
     * 当前登陆管理员ID
     */
    protected $adminId;

    /**
     * 当前登陆管理员账号
     */
    protected $adminName;

    /**
     * 逻辑层注入
     */
    protected $logic;

    /**
     * 验证器注入
     */
    protected $validate;

    /**
     * 默认主键
     */
    protected $pk = 'id';

    /**
     * 构造方法
     * @access public
     */
    public function __construct()
    {
        // 控制器初始化
        $this->init();
    }

    /**
     * 成功返回json内容
     * @param $data
     * @param $msg
     * @return Response
     */
    public function success($data = [], $msg = 'success'): Response
    {
        if (is_string($data)) {
            $msg = $data;
        }
        return json(['code' => 200, 'message' => $msg, 'data' => $data]);
    }

    /**
     * 失败返回json内容
     * @param $msg
     * @return Response
     */
    public function fail($msg = 'fail'): Response
    {
        return json(['code' => 400, 'message' => $msg]);
    }

    /**
     * 初始化
     */
    protected function init()
    {
        // 检查默认请求类型
        $this->checkDefaultMethod();

        $logic  = new SystemUserLogic();
        $result = getCurrentInfo();
        if (! $result) {
            throw new ApiException('未授权，请先登录', 401);
        }
        $this->adminId   = $result['id'];
        $this->adminName = $result['username'];
        $this->adminInfo = $logic->read($result['id']);

        // 接口权限认证
        $server_auth = config('plugin.saiadmin.saithink.server_auth', false);
        if ($server_auth) {
            $this->adminId > 1 && $this->checkAuth();
        }

        // 用户数据传递给逻辑层
        $this->logic && $this->logic->init($this->adminInfo);
    }

    /**
     * 接口权限认证
     */
    protected function checkAuth()
    {
        // 接口请求权限判断
        $path = request()->path();
        if (preg_match("/\d*$/", $path)) {
            $path = rtrim(preg_replace("/\d*$/", '', $path), '/');
        }
        // 当前请求 接口权限
        $logic  = new SystemMenuLogic();
        $routes = $logic->getAllCode();

        if (in_array($path, $routes)) {
            // 请求接口有权限配置则进行验证
            $role_ids   = Arr::getArrayColumn($this->adminInfo['roleList'], 'id');
            $roleLogic  = new SystemRoleLogic();
            $roles      = $roleLogic->getMenuIdsByRoleIds($role_ids);
            $ids        = $logic->filterMenuIds($roles);
            $allowCodes = $logic->getMenuCode($ids);
            if (! in_array($path, $allowCodes)) {
                throw new ApiException('您没有权限访问', 403);
            }
        }
    }

    /**
     * 检查默认方法
     * @return void
     */
    protected function checkDefaultMethod()
    {
        $functions = [
            'index'        => 'get',
            'save'         => 'post',
            'update'       => 'put',
            'read'         => 'get',
            'changestatus' => 'post',
            'destroy'      => 'delete',
            'recycle'      => 'get',
            'recovery'     => 'post',
            'realdestroy'  => 'delete',
            'import'       => 'post',
            'export'       => 'post',
        ];
        $action = strtolower(request()->action);
        if (array_key_exists($action, $functions)) {
            $this->checkMethod($functions[$action]);
        }
    }

    /**
     * 验证请求方式
     * @param string $method
     * @return void
     */
    protected function checkMethod(string $method)
    {
        $m = strtolower(request()->method());
        if ($m !== strtolower($method)) {
            throw new ApiException('Not Found!', 404);
        }
    }

    /**
     * 保存数据
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
            $this->afterChange('save');
            return $this->success('操作成功');
        } else {
            return $this->fail('操作失败');
        }
    }

    /**
     * 修改数据
     * @param $id
     * @param Request $request
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
            $this->afterChange('update');
            return $this->success('操作成功');
        } else {
            return $this->fail('操作失败');
        }
    }

    /**
     * 修改状态
     * @param Request $request
     * @return Response
     */
    public function changeStatus(Request $request): Response
    {
        $id     = $request->input('id', '');
        $status = $request->input('status', 1);
        $result = $this->logic->where($this->pk, $id)->update(['status' => $status]);
        if ($result) {
            $this->afterChange('changeStatus');
            return $this->success('操作成功');
        } else {
            return $this->fail('操作失败');
        }
    }

    /**
     * 删除数据
     * @param Request $request
     * @return Response
     */
    public function destroy(Request $request): Response
    {
        $ids = $request->input('ids', '');
        if (! empty($ids)) {
            $this->logic->destroy($ids);
            $this->afterChange('destroy');
            return $this->success('操作成功');
        } else {
            return $this->fail('参数错误，请检查');
        }
    }

    /**
     * 读取数据
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
     * 回收站数据
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
        return $this->success($data);
    }

    /**
     * 恢复数据
     * @param Request $request
     * @return Response
     */
    public function recovery(Request $request): Response
    {
        $ids = $request->input('ids', '');
        if (! empty($ids)) {
            $this->logic->restore($ids);
            $this->afterChange('recovery');
            return $this->success('恢复成功');
        } else {
            return $this->fail('参数错误，请检查');
        }
    }

    /**
     * 数据销毁-真实删除
     * @param Request $request
     * @return Response
     */
    public function realDestroy(Request $request): Response
    {
        $ids = $request->input('ids', '');
        if (! empty($ids)) {
            $this->logic->destroy($ids, true);
            $this->afterChange('realDestroy');
            return $this->success('操作成功');
        } else {
            return $this->fail('参数错误，请检查');
        }
    }

    /**
     * 数据改变后执行
     * @return void
     */
    public function afterChange($type)
    {
        // todo
    }
}
