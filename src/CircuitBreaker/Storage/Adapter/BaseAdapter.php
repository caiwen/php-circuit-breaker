<?php
/**
 * Created by PhpStorm.
 * User: caiwen
 * Date: 2019/1/2
 * Time: 17:33
 * 适配器基类
 */
namespace Globalegrow\CircuitBreaker\Storage\Adapter;
use Globalegrow\CircuitBreaker\Storage\StorageInterface;

abstract class BaseAdapter implements StorageInterface
{
    /**
     * @var int 取值为秒, 保存在缓存中的时间
     */
    protected $ttl;

    /**
     * @var string 缓存前缀
     */
    protected $cachePrefix = "CircuitBreaker_";

    /**
     * 配置初始化
     * BaseAdapter constructor.
     * @param int $ttl 缓存时间
     * @param bool $cachePrefix 缓存前缀
     */
    public function __construct($cachePrefix = false)
    {
        if ($cachePrefix && is_string($cachePrefix)) {
            $this->cachePrefix = $cachePrefix;
        }
    }

    /**
     * 获取数据
     * @param $key
     * @return mixed
     */
    public function getData ($key)
    {
        // 确保扩展可用
        $this->checkExtension();
        return $this->get($this->cachePrefix.$key);
    }

    /**
     * 载入数据
     * @param $keyList
     * @return mixed|string
     */
    public function loadData($keyList)
    {
        // 确保扩展可用
        $this->checkExtension();
        $keyList = array_map(function ($v) {
            return $this->cachePrefix.$v;
        },$keyList);

        $data = $this->mGet($keyList);

        if (empty($data)) {
            return "";
        }

        return $data;
    }

    /**
     * 保存数据
     * @param $key
     * @param $value
     * @param int $ttl
     * @return mixed|void
     */
    public function saveData ($key, $value,$ttl = 3600)
    {
        // 确保扩展已经加载
        $this->checkExtension();
        $this->set($this->cachePrefix.$key, $value,$ttl);
    }

    /**
     * 递增
     * @param $key
     * @param int $incValue
     * @return mixed
     */
    public function increment ($key, $incValue = 1)
    {
        // 确保扩展已经加载
        $this->checkExtension();
        return $this->inc($this->cachePrefix.$key,$incValue);
    }

    /**
     * 递减
     * @param $key
     * @param int $incValue
     * @return mixed
     */
    public function decrement ($key, $incValue = 1)
    {
        // 确保扩展已经加载
        $this->checkExtension();
        return $this->decr($this->cachePrefix.$key,$incValue);
    }

    /**
     * 删除数据
     * @param $key
     * @return mixed
     */
    public function dropData ($key)
    {
        // 确保扩展已经加载
        $this->checkExtension();
        return $this->delete($this->cachePrefix.$key);
    }

    /**
     * 获取过期时间
     * @param $key
     * @return mixed
     */
    public function getExpireTime ($key)
    {
        // 确保扩展已经加载
        $this->checkExtension();
        return $this->getExpire($this->cachePrefix.$key);
    }

    /**
     * 确保扩展是可用的
     * @return mixed
     */
    abstract protected function checkExtension();

    abstract protected function get($key);

    abstract protected function set($key, $value, $expireSeconds);

    abstract protected function inc($key,$incValue);

    abstract protected function mGet($keyList);

    abstract protected function decr($key,$decrValue);

    abstract protected function delete($keys);

    abstract protected function getExpire($key);
}