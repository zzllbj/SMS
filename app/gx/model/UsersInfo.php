<?php
// +----------------------------------------------------------------------
// | admin [ 学无止境 ]
// +----------------------------------------------------------------------
// | Author: zzllbj@126.com
// +----------------------------------------------------------------------
namespace app\gx\model;

use plugin\saiadmin\basic\BaseModel;

/**
 * 人员信息模型
 */
class UsersInfo extends BaseModel
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
    protected $table = 'gx_users';


    /**
     * 证书保存数组转换
     */
    public function setCertAttr($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

     /**
     * 证书读取数组转换
     */
    public function getCertAttr($value)
    {
        return json_decode($value ?? '', true);
    }
    
    /**
     * 姓名 搜索
     */
    public function searchNameAttr($query, $value)
    {
        $query->where('name', 'like', '%'.$value.'%');
    }
    
    /**
     * 职称 搜索
     */
    public function searchJobtitleAttr($query, $value)
    {
        $query->where('jobtitle', 'like', '%'.$value.'%');
    }


}
