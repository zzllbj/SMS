<?php

namespace plugin\admin\app\controller;

use support\Request;
use support\Response;
use plugin\admin\app\model\WfProcessForm;
use plugin\admin\app\controller\Crud;
use support\exception\BusinessException;

/**
 * 表单设计 
 */
class WfProcessFormController extends Crud
{
    
    /**
     * @var WfProcessForm
     */
    protected $model = null;

    /**
     * 构造函数
     * @return void
     */
    public function __construct()
    {
        $this->model = new WfProcessForm;
    }
    
    /**
     * 浏览
     * @return Response
     */
    public function index(): Response
    {
        return view('wf-process-form/index');
    }

    /**
     * 插入
     * @param Request $request
     * @return Response
     * @throws BusinessException
     */
    public function insert(Request $request): Response
    {
        if ($request->method() === 'POST') {
            return parent::insert($request);
        }
        return view('wf-process-form/insert');
    }

    /**
     * 更新
     * @param Request $request
     * @return Response
     * @throws BusinessException
    */
    public function update(Request $request): Response
    {
        if ($request->method() === 'POST') {
            return parent::update($request);
        }
        return view('wf-process-form/update');
    }

}
