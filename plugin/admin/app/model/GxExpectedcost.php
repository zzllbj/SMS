<?php

namespace plugin\admin\app\model;

use plugin\admin\app\model\Base;

/**
 * @property integer $id 主键(主键)
 * @property string $create_time 创建时间
 * @property string $update_time 更新时间
 * @property string $delete_time 软删除
 * @property string $name_project 项目名称
 * @property string $name 课题名称
 * @property string $Imputation_type 归集类型
 * @property integer $user_fee 人员费
 * @property integer $material_fee 材料费
 * @property integer $other_fee 其他费
 * @property string $name_subjects 科目名称
 * @property string $remark 备注信息
 * @property string $expected_date 预归集日期
 */
class GxExpectedcost extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gx_expectedcost';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    
    
}
