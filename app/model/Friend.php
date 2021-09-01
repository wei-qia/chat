<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Friend extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'friends';

    public function isFriend($user_id, $friend_id){
        $user = $this->where('user_id', $user_id)->where('friend_id', $friend_id)->find();
        $friend = $this->where('user_id', $friend_id)->where('friend_id', $user_id)->find();
        if(!$user || !$friend){
            return false;
        }else{
            return true;
        }
    }

    public function friendList($user_id){
        return self::with('username')
            ->where('user_id',$user_id)
            ->where('status',1)
            ->field(['friend_id'])
            ->select();
    }

    public function username(){
        return $this->belongsTo(User::class, 'friend_id')->bind(['username']);
    }
}
