<?php
namespace app\api\controller;

use app\common\controller\DataModel;

header('Content-Type: application/json');

class Data extends Base
{
    public function getData()
    {
        try {
            $dataModel = new DataModel();
            // 查找数据
//            $data = $dataModel->where('id', $this->aid)->select();
            $data = $dataModel->select();
            // 判断数据是否为空
            if (!$data) {
                return json(['code' => 0, 'msg' => '没有数据']);
            } else {
                return json(['code' => 1, 'msg' => '数据查询成功', 'data' => $data]);
            }
        } catch (\Exception $e) {
            // 如果出现错误，返回错误信息
            return json(['code' => 0, 'msg' => '查询失败', 'error' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {

        if (!$id) {
            return json(['code' => 0, 'msg' => '无效的 ID']);
        }
        $result = db('data')->where('id', $id)->delete();

        if ($result) {
            return json(['code' => 1, 'msg' => '删除成功']);
        } else {
            return json(['code' => 0, 'msg' => '删除失败']);
        }
    }
    // 添加数据的方法
    public function insert(): \think\response\Json
    {
        // 获取前端传递的 JSON 数据+
        $inputData = input('post.');
        error_log(json_encode($inputData));
        // 检查必填字段是否存在
        if (empty($inputData['data']) || empty($inputData['name']) || empty($inputData['address'])) {
            return json(['code' => 0, 'msg' => '缺少必要的字段']);
        }

        // 处理数据
        $data = [
            'data' => $inputData['data'],
            'name' => $inputData['name'],
            'address' => $inputData['address'],
        ];

        // 插入数据库
        try {
            $result = db('data')->insert($data); // 假设表名是 'data'

            if ($result) {
                return json(['code' => 1, 'msg' => '数据添加成功']);
            } else {
                return json(['code' => 0, 'msg' => '数据添加失败']);
            }
        } catch (\Exception $e) {
            // 如果插入过程中发生错误，返回错误信息
            return json(['code' => 0, 'msg' => '添加失败', 'error' => $e->getMessage()]);
        }
    }

    public function update($id)
    {
        try {
            // 获取 POST 数据
            $inputData = input('post.');

            // 检查必填字段
            if (empty($inputData['data']) || empty($inputData['name']) || empty($inputData['address'])) {
                return json(['code' => 0, 'msg' => '缺少必要的字段']);
            }

            // 数据处理并更新到数据库
            $data = [
                'data' => $inputData['data'],
                'name' => $inputData['name'],
                'address' => $inputData['address'],
            ];

            // 更新数据库
            $result = db('data')->where('id', $id)->update($data);

            if ($result) {
                return json(['code' => 1, 'msg' => '数据更新成功']);
            } else {
                return json(['code' => 0, 'msg' => '数据更新失败']);
            }
        } catch (\Exception $e) {
            return json(['code' => 0, 'msg' => '更新失败', 'error' => $e->getMessage()]);
        }
    }


}

?>
