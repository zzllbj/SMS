<?php

// +----------------------------------------------------------------------
// | saithink [ saithink快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: sai <1430792918@qq.com>
// +----------------------------------------------------------------------
namespace plugin\saiadmin\app\controller\system;

use plugin\saiadmin\basic\BaseController;
use plugin\saiadmin\app\logic\system\SystemConfigGroupLogic;
use plugin\saiadmin\app\validate\system\SystemConfigGroupValidate;
use plugin\saiadmin\utils\Arr;
use support\Request;
use support\Response;
use plugin\saiadmin\service\EmailService;
use plugin\saiadmin\app\model\system\SystemMail;

/**
 * 配置控制器
 */
class SystemConfigGroupController extends BaseController
{
    /**
     * 构造
     */
    public function __construct()
    {
        $this->logic = new SystemConfigGroupLogic();
        $this->validate = new SystemConfigGroupValidate;
        parent::__construct();
    }

    /**
     * 数据列表
     * @param Request $request
     * @return Response
     */
    public function index(Request $request) : Response
    {
        $where = $request->more([
            ['name', ''],
            ['code', ''],
        ]);
        $query = $this->logic->search($where);
        $data = $this->logic->getAll($query);
        return $this->success($data);
    }

    /**
     * 邮件测试
     * @param Request $request
     * @return Response
     */
    public function email(Request $request) : Response
    {
        $email = $request->input('email', '');
        if (empty($email)) {
            return $this->fail('请输入邮箱');
        }
        $subject = "测试邮件";
        $code = "9527";
        $content = "<h1>验证码：{code}</h1><p>这是一封测试邮件,请忽略</p>";
        $template = [
            'code' => $code
        ];
        $config = EmailService::getConfig();
        $model = SystemMail::create([
            'gateway' => Arr::getConfigValue($config,'Host'),
            'from' => Arr::getConfigValue($config,'From'),
            'email' => $email,
            'code' => $code,
        ]);
        try {
            $result = EmailService::sendByTemplate($email, $subject, $content, $template);
            if (!empty($result)) {
                $model->status = 'failure';
                $model->response = $result;
                $model->save();
                return $this->fail('发送失败，请查看日志');
            } else {
                $model->status = 'success';
                $model->save();
                return $this->success([], '发送成功');
            }
        } catch (\Exception $e) {
            $model->status = 'failure';
            $model->response = $e->getMessage();
            $model->save();
            return $this->fail($e->getMessage());
        }
    }

}
