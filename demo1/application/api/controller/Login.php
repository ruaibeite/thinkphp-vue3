<?php

namespace app\api\controller;
use app\common\controller\AdminModel;
use think\Controller;
use think\Request;
use \Firebase\JWT\JWT;

class Login extends Cross
{
    /**
     * 登录方法
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function login(Request $request)
    {
        // 获取请求参数
        $name = $request->param('name');
        $password = $request->param('password');

        // 查询用户信息
        $admin = new AdminModel();
        $info = $admin->where('name', $name)->find();

        // 用户不存在
        if (!$info) {
            return json(['code' => 0, 'msg' => '用户不存在']);
        }


        if (md5($password) != $info['password']) {
            return json(['code' => 0, 'msg' => '账号或者密码错误']);
        }

        // 生成 JWT token
        $jwt = new JWT();
        $key = '123456';  // 这只是一个密钥，用于 JWT 编码
        $payload = [
            'iss' => 'localhost',
            'aud' => 'localhost',
            'iat' => time(),
            'nbf' => time(),
            'aid' => $info['id'],
        ];

        // 生成 token
        $token = $jwt->encode($payload, $key,'HS256');

        // 登录成功，返回 token
        return json(['code' => 1, 'msg' => '登录成功', 'token' => $token]);
    }
}
