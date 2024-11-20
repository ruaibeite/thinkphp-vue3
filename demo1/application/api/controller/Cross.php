<?php

namespace app\api\controller;

use think\Controller;
use think\Request;

class Cross extends Controller
{
    protected function initialize()
    {
        // 设置 CORS 头部
        header('Access-Control-Allow-Origin: *');  // 允许所有来源
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');  // 允许的请求方法
        header('Access-Control-Allow-Headers: id,Authorization,Content-Type, X-Requested-With,token');  // 允许的请求头
    if(request()->isOptions()){
        exit();
    }
    }
    public function index()
    {
        //
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
