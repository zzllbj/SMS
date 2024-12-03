<?php

namespace plugin\admin\app\model;

use plugin\admin\app\model\Base;

/**
 * @property integer $id 主键(主键)
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property string $name 姓名
 * @property integer $sex 性别
 * @property integer $age 年龄
 * @property string $tel 电话
 * @property string $cert 证书
 * @property integer $professional 专业
 * @property integer $jobtitle 职称
 * @property integer $educated 教育
 * @property integer $type 类型
 * @property integer $department 部门
 * @property integer $delete_time 软删除
 */
class GxUser extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gx_users';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    
    
}
