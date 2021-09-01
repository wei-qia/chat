<?php
declare (strict_types = 1);

namespace app\api\middleware;

use app\common\lib\Redis;
use thans\jwt\exception\JWTException;
use thans\jwt\exception\TokenBlacklistException;
use thans\jwt\exception\TokenBlacklistGracePeriodException;
use thans\jwt\exception\TokenExpiredException;

use thans\jwt\middleware\JWTAuth;
use think\Exception;

class checkJWT extends JWTAuth
{

    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        // 旧token
        $redis = new Redis();
        if(!$this->auth->token()){
            throw new Exception('授权已过期', 401);
        }
        $old_token = $this->auth->token()->get();
        $user = $redis->get(config('redis.token_pre') . $old_token);
        if(!$user){
            throw new Exception('授权已过期', 401);
        }
        // 验证token
        try {
            $this->auth->auth();
        } catch (TokenExpiredException $e) { // 捕获token过期
            // 尝试刷新token
            try {

                $this->auth->setRefresh();
                $token = $this->auth->refresh();

                // 删除掉redis原数据添加新数据
                $redis->set(config('redis.token_pre') . $old_token, $user, 30);
                $redis->set(config('redis.token_pre') . $token, $user);

                $response = $next($request);
                return $this->setAuthentication($response, $token);
            } catch (TokenBlacklistGracePeriodException $e) { // 捕获黑名单宽限期

                return $next($request);
            } catch (JWTException $exception) {
                // 如果捕获到此异常，即代表 refresh 也过期了，用户无法刷新令牌，需要重新登录。
                throw new Exception($exception->getMessage(), 401);
            }
        } catch (TokenBlacklistException  $e) { // 捕获黑名单宽限期
            throw new Exception('授权已过期', 401);
        }

        return $next($request);
    }
}
