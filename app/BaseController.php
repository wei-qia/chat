<?php
declare (strict_types = 1);

namespace app;

use app\common\lib\Redis;
use thans\jwt\facade\JWTAuth;
use think\App;
use think\exception\ValidateException;
use think\Validate;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * redis实例
     * @var array
     */
    protected $redis = NULL;

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        $this->redis = new Redis();

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {}

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }

    public function __call($name, $argument){
        return $this->fail("找不到{$name}方法");
    }

    public function show($status, $message, $data){
        return return_res($status, $message, $data);
    }

    public function success($data){
        return $this->show(
          config('status.success'),
          config('message.success'),
          $data
        );
    }

    public function fail($data){
        return $this->show(
            config('status.fail'),
            $data,
            NULL
        );
    }

    public function getToken(){
        return JWTAuth::token()->get();
    }

    public function getUser(){
        return $this->redis->get(config('redis.token_pre') . $this->getToken());
    }

    public function getUserId(){
        return $this->getUser()['id'];
    }
}