<?php
// +----------------------------------------------------------------------
// | admin [ 学无止境 ]
// +----------------------------------------------------------------------
// | Author: zzllbj@126.com
// +----------------------------------------------------------------------
namespace app\gx\logic;

use app\gx\model\CentralizeCapital;
use plugin\saiadmin\basic\BaseLogic;

/**
 * 资金归集逻辑层
 */
class CentralizeCapitalLogic extends BaseLogic
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new CentralizeCapital();
    }

    public function format_number()
    {
        var_dump($data);
        return $data;
    }

}
