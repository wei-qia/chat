<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class User extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'users';

    // 自动时间戳
    protected $autoWriteTimestamp = true;

    // 状态获取器
    public function getStatusStrAttr($value, $data)
    {
        $status = [0=>'禁用',1=>'正常',2=>'待审核'];
        return $status[$data['status']];
    }

    /**
     * @param $id
     */
    public function getUserByIdWithStatus($id){
        return $this->where('id', $id)->where('status', 1)->find();
    }

    /**
     * @param $username
     */
    public function getUserByUsernameWithStatus($username){
        return $this->where('username', $username)->where('status', 1)->find();
    }
}
