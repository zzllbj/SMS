<?php

namespace plugin\admin\app\model;

use plugin\admin\app\model\Base;

/**
 * @property string $id 主键(主键)
 * @property string $process_design_id 流程设计ID
 * @property mixed $content 流程模型定义
 * @property integer $create_time 创建时间
 * @property string $create_user 创建用户
 * @property float $versions 版本
 */
class WfProcessDesignHistory extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wf_process_design_history';

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
