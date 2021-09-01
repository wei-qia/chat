<?php


namespace app\common\exception;


use think\exception\Handle;
use think\Response;

class Http extends Handle
{
    private $msg = NULL;
    private $status = NULL;

    public function render($request, \Throwable $e): Response{
        $this->msg = $e->getMessage();
        $this->status = config('status.fail');
        if($this->msg == config('status.goto')){
            $this->status = config('status.goto');
        }
        // JWT验证不通过
        if($e->getCode() == 401){
            return json([
                'status' => config('status.forbidden'),
                'message' => $this->msg,
                'data' => NULL
            ]);
        }
        return json([
           'status' => $this->status,
            'message' => $this->msg,
            'data' => NULL
        ]);
    }
}