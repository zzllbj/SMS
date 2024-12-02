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
use app\gx\model\UsersInfo;

/**
 * 人员信息逻辑层
 */
class UsersInfoLogic extends BaseLogic
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new UsersInfo();
    }

    /**
     * 这里实现返回的born_date字段的日期计算年龄大小
     */
    public function computerage($userlistdata): array
    {
        // 用于计算年龄的方法
        //var_dump($userlistdata);
        if (is_array($userlistdata) && isset($userlistdata['data'])) {
            foreach ($userlistdata['data'] as &$userInfo) {
                // 获取出生日期
                $bornDate = $userInfo['born_date'] ?? null;
                //var_dump($bornDate);
                //echo $bornDate;
                if ($bornDate) {
                    // 将出生日期字符串转换为日期对象
                    $birthDate = new \DateTime($bornDate);
                    // 获取当前日期对象
                    $currentDate = new \DateTime('now');
                    // 计算年龄
                    $age = $currentDate->diff($birthDate)->y;
                    // 更新人员信息数据中的年龄字段
                    //var_dump($age);
                    $userInfo['age'] = $age;
                }
            }
        }
        // 返回更新后的人员信息数据列表
        return $userlistdata;
    }


}
