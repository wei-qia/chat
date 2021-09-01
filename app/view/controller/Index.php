<?php
declare (strict_types = 1);

namespace app\view\controller;

use app\BaseController;
use think\facade\View;

class Index extends BaseController
{
    public function index()
    {
        return View::fetch('index/index');
    }

    public function room($id)
    {
        View::assign('id',$id);
        return View::fetch('index/room');
    }
}
