<?php

namespace plugin\admin\app\model;

use plugin\admin\app\model\Base;

/**
 * @property string $id 主键(主键)
 * @property string $name 唯一编码
 * @property string $display_name 显示名称
 * @property string $description 描述
 * @property string $type_id 流程分类
 * @property string $icon 图标
 * @property integer $is_deployed 是否已部署
 * @property integer $create_time 创建时间
 * @property string $create_user 创建用户
 * @property integer $update_time 更新时间
 * @property string $update_user 更新用户
 * @property integer $is_del 是否删除
 * @property string $remark 备注
 */
class WfProcessForm extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wf_process_form';

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

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    
}
