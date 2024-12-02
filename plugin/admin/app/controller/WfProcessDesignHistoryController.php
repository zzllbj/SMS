<?php

namespace plugin\admin\app\controller;

use support\Request;
use support\Response;
use plugin\admin\app\model\WfProcessDesignHistory;
use plugin\admin\app\controller\Crud;
use support\exception\BusinessException;

/**
 * 历史流程设计 
 */
class WfProcessDesignHistoryController extends Crud
{
    
    /**
     * @var WfProcessDesignHistory
     */
    protected $model = null;

    /**
     * 构造函数
     * @return void
     */
    public function __construct()
    {
        $this->model = new WfProcessDesignHistory;
    }
    
    /**
     * 浏览
     * @return Response
     */
    public function index(): Response
    {
        return view('wf-process-design-history/index');
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
        return view('wf-process-design-history/insert');
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
        return view('wf-process-design-history/update');
    }

}
