<?php

namespace plugin\admin\app\controller;

use support\Request;
use support\Response;
use plugin\admin\app\model\GxExpectedcost;
use plugin\admin\app\controller\Crud;
use support\exception\BusinessException;

/**
 * 预计归集录入 
 */
class GxExpectedcostController extends Crud
{
    
    /**
     * @var GxExpectedcost
     */
    protected $model = null;

    /**
     * 构造函数
     * @return void
     */
    public function __construct()
    {
        $this->model = new GxExpectedcost;
    }
    
    /**
     * 浏览
     * @return Response
     */
    public function index(): Response
    {
        return view('gx-expectedcost/index');
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
        return view('gx-expectedcost/insert');
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
        return view('gx-expectedcost/update');
    }

}
