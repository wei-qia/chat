<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Chat extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'chats';

    // 自动时间戳
    protected $autoWriteTimestamp = true;

    public function record($user_id, $friend_id){
        return $this->whereOr([
            [
                ['user_id', '=', $user_id],
                ['friend_id', '=', $friend_id]
            ],
            [
                ['user_id', '=', $friend_id],
                ['friend_id', '=', $user_id]
            ]
        ])
            ->select();

    }
}
