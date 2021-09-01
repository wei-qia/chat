<?php

namespace app\common\lib;


use think\facade\Cache;
use think\cache\driver\Redis as R;

class Redis
{
    private $store = null;
    private $redis = null;

    public function __construct($store = 'redis'){
        $this->setStore($store);
        $this->redis = new R(config('cache.stores.'.$store));
    }

    public function setStore($store){
        $this->store = $store;
        return $this;
    }

    public function set($key, $value, $ttl = NULL){
        return Cache::store($this->store)->set($key, $value, $ttl);
    }

    public function get($key){
        return Cache::store($this->store)->get($key);
    }

    public function delete($key){
        return Cache::store($this->store)->delete($key);
    }

    /**
     * 事务相关扩展
     */
    public function rset($key, $value, $ttl = NULL){
        return $this->redis->set($key, $value, $ttl = NULL);
    }
    public function multi(){
        return $this->redis->multi();
    }

    public function exec(){
        return $this->redis->exec();
    }

    public function discard(){
        return $this->redis->discard();
    }
}