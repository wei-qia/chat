<?php


namespace app\api\controller;

use app\BaseController;
use app\model\Friend as friendModel;
use app\model\User as userModel;
use Exception;
use thans\jwt\facade\JWTAuth;
use think\App;
use think\facade\Db;

class User extends BaseController
{

    private $userModel = null;
    private $friendModel = null;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->userModel = new userModel();
        $this->friendModel = new friendModel();
    }

    /**
     * 用户注册
     */
    public function register(){
        $this->userModel->username = $this->request->param('username','','htmlspecialchars');
        $this->userModel->password = md5(md5($this->request->param('password','','htmlspecialchars')));

        $this->userModel->save();

        return $this->success($this->userModel);
    }

    /**
     * 用户登录
     */
    public function login(){
        $username = $this->request->param('username','','htmlspecialchars');
        $password = md5(md5($this->request->param('password','','htmlspecialchars')));

        $user = $this->userModel->where('username',$username)->where('password',$password)->find();
        if($user){
            if($user['status']!=1){
                return $this->fail('账号已被禁用');
            }
            $token = JWTAuth::builder(['uid' => $user->id]);
            $this->redis->set(config('redis.token_pre') . $token, $user);
            $user['token'] = $token;
            $user['password'] = '';
            return $this->success($user);
        }else{
            return $this->fail('账号不存在或密码错误');
        }

    }

    /**
     * 用户注销
     */
    public function logout(){
        // 拉黑token 清除redis缓存
        $token = $this->getToken();
        JWTAuth::invalidate($token);
        $this->redis->delete(config('redis.token_pre') . $token);
        return $this->success('');

    }

    /**
     * 添加好友
     */
    public function addFriend(){
        $username = $this->request->param('username');
        $message = $this->request->param('message');
        $user = $this->getUser();
        $token = $this->getToken();
        $type = 'public'; // 服务器请求类型为public
        $friend = $this->userModel->getUserByUsernameWithStatus($username);
        if(!$friend){
            return $this->fail('用户不存在');
        }

        $socket = $this->redis->get(config('redis.socket_pre').$friend['id']);

        // 查看对面的好友申请列表里面有没有该记录
        if(!empty($socket['apply_list'])){
            foreach ($socket['apply_list'] as $key => $value){
                if($key == $user['id']){
                    return $this->fail('该申请记录已存在');
                }
            }
        }

        if($this->friendModel->isFriend($user['id'], $friend['id'])){
            return $this->fail('你们已是好友');
        }

        if($user['id'] == $friend['id']){
            return $this->fail('不能加自己为好友');
        }

        $send = [
            'type' => 'addFriend',
            'uid' => $user['id'],
            'username' => $user['username'],
            'target' => $friend['id'],
            'message' => $message,
        ];

        if(!swooleCurl($type, $token, $send)){
            return $this->fail('发送失败');
        }else{
            return $this->success([]);
        }
    }

    /**
     * 处理好友请求
     */
    public function handleFriend(){
        $friend_id = $this->request->param('friend_id');
        $handle_status = $this->request->param('handle_status');
        $user = $this->getUser();
        $token = $this->getToken();
        $type = 'public'; // 服务器请求类型为public
        $friend = $this->userModel->getUserByIdWithStatus($friend_id);
        if(!$friend){
            return $this->fail('用户不存在');
        }

        $socket = $this->redis->get(config('redis.socket_pre').$user['id']);

        // 查看好友申请列表里面有没有该记录
        if(empty($socket['apply_list']) || !array_key_exists($friend_id, $socket['apply_list'])){
            return $this->fail('该申请记录不存在');
        }

        if($this->friendModel->isFriend($user['id'], $friend['id'])){
            unset($socket['apply_list'][$friend_id]);
            $this->redis->set(config('redis.socket_pre').$user['id'], $socket);
            return $this->fail('你们已是好友');
        }

        if($user['id'] == $friend['id']){
            unset($socket['apply_list'][$friend_id]);
            $this->redis->set(config('redis.socket_pre').$user['id'], $socket);
            return $this->fail('不能加自己为好友');
        }

        // 开启事务
        Db::startTrans();
        try{
            // 开启redis事务
            $this->redis->multi();
            if((boolean)$handle_status){
                $list = [
                    [
                        'user_id' => $user['id'],
                        'friend_id' => $friend_id,
                    ],
                    [
                        'user_id' => $friend_id,
                        'friend_id' => $user['id'],
                    ]
                ];
                $this->friendModel->saveAll($list);
            }
            unset($socket['apply_list'][$friend_id]);
            $this->redis->rset(config('redis.socket_pre').$user['id'], $socket);
            $this->redis->exec();
            Db::commit();

            // 执行成功推送信息给对方客户端 通知其更新信息
            $send = [
                'type' => 'handleFriend',
                'uid' => $user['id'],
                'username' => $user['username'],
                'target' => $friend['id'],
            ];

            swooleCurl($type, $token, $send);

            return $this->success($friend);
        } catch (Exception $e){
            $this->redis->discard();
            Db::rollback();

            return $this->fail('操作失败, 原因：'.$e->getMessage());
        }
    }
}