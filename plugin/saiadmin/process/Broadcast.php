<?php
// +----------------------------------------------------------------------
// | 学无止境
// +----------------------------------------------------------------------
// | Author: zzllbj@126.com
// +----------------------------------------------------------------------
namespace plugin\saiadmin\process;

use Webman\Push\Api;
use Workerman\Crontab\Crontab;

class Broadcast
{
    public function onWorkerStart()
    {
        //每分钟的第一秒执行
        //系统公告
        new Crontab('1 * * * * *', function () {
            echo '广播推送：' . date('Y-m-d H:i:s') . "\n";

            $api = new Api(
                // webman下可以直接使用config获取配置，非webman环境需要手动写入相应配置
                config('plugin.webman.push.app.api'),
                config('plugin.webman.push.app.app_key'),
                config('plugin.webman.push.app.app_secret'),
            );
            // 给订阅 saiadmin 的所有客户端推送 message 事件的消息
            $return_Broadcast = [
                'event'   => 'success',
                'message' => 'crontab广播',
                'data'    => [
                    [
                        'id'          => 1,
                        'title'       => '系统消息',
                        'content'     => '推送消息成功',
                        'create_time' => date('Y-m-d H:i:s'),
                        'send_user'   => [
                            'nickname' => '管理员',
                            'avatar'   => '',
                        ],
                    ],
                ],
            ];
            // 给订阅 saiadmin 的所有客户端推送 message 事件的消息
            $api->trigger('saiadmin', 'message', $return_Broadcast);

        });
    }

    // public function initStart()
    // {
    //     $logic    = new SystemCrontabLogic();
    //     $taskList = $logic->where('status', 1)->select();
    //     foreach ($taskList as $item) {
    //         new Crontab($item->rule, function () use ($logic, $item) {
    //             $logic->run($item->id);
    //         });
    //     }
    // }

    // public function reload()
    // {
    //     echo '重载成功->' . date('Y-m-d H:i:s') . "\n";
    //     $list = Crontab::getAll();
    //     foreach ($list as $item) {
    //         Crontab::remove($item->getId());
    //     }
    //     $this->initStart();
    // }

    // public function run($args)
    // {
    //     echo '任务调用：' . date('Y-m-d H:i:s') . "\n";
    //     //var_dump('参数:' . $args);
    //     $api = new Api(
    //         config('plugin.webman.push.app.api'),
    //         config('plugin.webman.push.app.app_key'),
    //         config('plugin.webman.push.app.app_secret')
    //     );
    //     // 给订阅 saiadmin 的所有客户端推送 message 事件的消息
    //     $return_ret = [
    //         'event'   => 'ev_new_message',
    //         'message' => '新消息通知',
    //         'data'    => [
    //             [
    //                 'id'          => 1,
    //                 'title'       => '系统消息',
    //                 'content'     => '推送消息成功',
    //                 'create_time' => date('Y-m-d H:i:s'),
    //                 'send_user'   => [
    //                     'nickname' => '系统管理员',
    //                     'avatar'   => '',
    //                 ],
    //             ],
    //         ],
    //     ];
    //     $api->trigger('saiadmin', 'message', $return_ret);
    // }
}
