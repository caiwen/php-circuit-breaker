<?php
/**
 * Created by PhpStorm.
 * User: caiwen
 * Date: 2019/1/3
 * Time: 19:41
 */

namespace Globalegrow\CircuitBreaker\Storage\Adapter;


use Predis\ClientInterface;

class RedisAdapter extends BaseAdapter
{
    protected $redis;

    public function __construct (ClientInterface $redis,$cachePrefix = false)
    {
        parent::__construct($cachePrefix);
        $this->redis = $redis;
    }

    /**
     * 检查扩展
     * @return mixed|void
     */
    protected function checkExtension()
    {
        //nothing to do
    }

    /**
     * 设置值
     * @param $key
     * @param $value
     * @param $expireSeconds
     * @return bool
     */
    protected function set($key , $value , $expireSeconds)
    {
        if($key) {
            $this->redis->setex($key,$expireSeconds,$value);
        }

        return true;
    }

    /**
     * 获取值
     * @param $key
     * @return string
     */
    protected function get($key)
    {
        return $this->redis->get($key);
    }

    /**
     * 批量获取值
     * @param $keyList
     * @return array
     */
    protected function mGet($keyList)
    {
        return $this->redis->mget($keyList);
    }

    /**
     * 递增
     * @param $key
     * @param $incValue
     * @return int
     */
    protected function inc($key, $incValue)
    {
        return $this->redis->incrby($key,$incValue);
    }

    /**
     * 递减
     * @param $key
     * @param $decrValue
     * @return int
     */
    protected function decr ($key, $decrValue)
    {
        return $this->redis->decrby($key,$decrValue);
    }

    /**
     * 删除
     * @param $keys
     * @return int
     */
    protected function delete ($keys)
    {
        return $this->redis->del($keys);
    }

    /**
     * 获取过期时间
     * @param $key
     * @return int
     */
    protected function getExpire ($key)
    {
        return$this->redis->ttl($key);
    }
}