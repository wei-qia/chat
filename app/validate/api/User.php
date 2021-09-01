<?php
declare (strict_types = 1);

namespace app\validate\api;

use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'username' => ['require','min:4','max:20','unique:users'],
        'password' => ['require','min:6','max:20'],
        'password_confirmation'=>['require','min:6','max:20','confirm:password'],
        'message' => ['max:20'],
        'friend_id' => ['require'],
        'handle_status' => ['require','between:0,1'],
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'username.require' => '用户名不能为空',
        'username.min' => '用户名不能少于4个字符',
        'username.max' => '用户名不能大于20个字符',
        'username.unique' => '用户名已存在',
        'password.require' => '密码不能为空',
        'password.min' => '密码不能少于6个字符',
        'password.max' => '密码不能大于6个字符',
        'password_confirmation.require' => '确认密码不能为空',
        'password_confirmation.min' => '确认密码不能少于6个字符',
        'password_confirmation.max' => '确认密码不能大于6个字符',
        'password_confirmation.confirm' => '两次密码不一致',
        'friend_id.require' => '好友id不能为空',
        'handle_status.require' => '请选择是否同意',
        'handle_status.between' => '请选择是否同意',
    ];

    /**
     * 定义验证场景
     *
     * @var array
     */
    protected $scene = [
        'register'  =>  ['username','password','password_confirmation'],
    ];

    // login 验证场景定义
    public function sceneLogin()
    {
        return $this->only(['username','password'])
            ->remove('username', 'unique');
    }

    // addFriend 验证场景定义
    public function sceneAddFriend()
    {
        return $this->only(['username','message'])
            ->remove('username', 'unique');
    }

    // addFriend 验证场景定义
    public function sceneHandleFriend()
    {
        return $this->only(['friend_id','handle_status']);
    }
}
