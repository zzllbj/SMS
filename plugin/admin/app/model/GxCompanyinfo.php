<?php

namespace plugin\admin\app\model;

use plugin\admin\app\model\Base;

/**
 * @property integer $id 主键(主键)
 * @property string $create_time 创建时间
 * @property string $update_time 更新时间
 * @property integer $delete_time 软删除
 * @property integer $name 单位名称
 * @property integer $social_credit_code 统一社会信用代码
 * @property integer $registered_capital 注册资金
 * @property integer $legal_person 法人
 * @property integer $paid_amount 实缴金额
 * @property string $create_date 成立日期
 * @property integer $enterprise_type 企业类型
 * @property string $business_main 主营业务
 * @property integer $remark 备注
 */
class GxCompanyinfo extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gx_companyinfo';

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
