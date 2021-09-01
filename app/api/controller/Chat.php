<?php


namespace app\api\controller;

use app\BaseController;

class Chat extends BaseController
{

    public function test(){
        $data=array();
        $data['type']='addFriend';
        $data['token']=$this->getToken();
        $data['message']='你好';
        swooleCurl('public', $this->getToken(), $data);
    }


}