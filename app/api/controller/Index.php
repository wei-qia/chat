<?php


namespace app\api\controller;


use app\BaseController;
use app\model\User as UserModel;
use app\model\Friend as FriendModel;
use app\model\Chat as ChatModel;

class Index extends BaseController
{
    public function index(){
        $friendModel = new FriendModel();
        $data=[
            'friend' => $friendModel->friendList($this->getUserId())
        ];
        return $this->success($data);
    }

    public function room(UserModel $user){
        $data = array();
        if(!$user){
            return $this->fail('用户不存在');
        }
        $user['password'] = '';
        $data['user'] = $user;
        $data['record'] = (new ChatModel())->record($this->getUserId(), $user['id']);
        return $this->success($data);
    }
}