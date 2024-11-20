<?php

namespace app\api\controller;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class Base extends Cross
{
    protected function initialize()
    {
        parent::initialize(); // 调用父类的初始化方法
        $header =request()->header();

        // 如果 token 不存在，直接返回错误
        if (empty($header['authorization'])) {
            return json(['code' => 0, 'msg' => 'Token 不存在，访问被拒绝'], 401)->send();
        }

        $jwt = new JWT();
        $key = '123456';
//        $info = $jwt::decode($header['token'], $key, ['HS256']);
        $info = JWT::decode($header['authorization'], new Key($key, 'HS256'));
        $this->aid = $info->aid;
    }
}
