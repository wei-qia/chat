<?php


namespace app\view\controller;

use app\BaseController;
use think\facade\View;

class User extends BaseController
{
    /**
     * 用户注册页面
     */
    public function register(){
        return View::fetch('user/register');
    }

    /**
     * 用户登录页面
     */
    public function login(){
        return View::fetch('user/login');
    }


}