<?php

namespace app\common\controller;

use think\Controller;
use think\Request;
use think\Model;
use \Firebase\JWT\JWT;
class DataModel extends Model
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    protected $table = 'data';
}