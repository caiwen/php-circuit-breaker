<?php
/**
 * Created by PhpStorm.
 * User: caiwen
 * Date: 2019/1/2
 * Time: 17:33
 * apc cache适配器
 */
namespace Globalegrow\CircuitBreaker\Storage\Adapter;
use Globalegrow\CircuitBreaker\Storage\StorageException;

class ApcAdapter extends BaseAdapter
{
    /**
     * 检查扩展
     * @return mixed|void
     * @throws StorageException
     */
    protected function checkExtension()
    {
        if (!function_exists("apc_store")) {
            throw new StorageException("APC extension not loaded.");
        }
    }

    /**
     * 设置
     * @param $key
     * @param $value
     * @param $expireSeconds
     * @return array|bool
     */
    protected function set($key , $value , $expireSeconds)
    {
        if($key) {
            return apc_store($key , $value , $expireSeconds);
        }

        return true;
    }

    /**
     * 获取
     * @param $key
     * @return mixed
     */
    protected function get($key)
    {
        return apc_fetch($key);
    }

    /**
     * 批量获取
     * @param $keyList
     * @return mixed
     */
    protected function mGet($keyList)
    {
        return apc_fetch($keyList);
    }

    /**
     * 递增
     * @param $key
     * @param $incValue
     * @return bool|int
     */
    protected function inc($key, $incValue)
    {
        return apc_inc($key , $incValue);
    }

    /**
     * 递减
     * @param $key
     * @param $decrValue
     * @return bool|int
     */
    protected function decr ($key, $decrValue)
    {
        return apc_dec($key,$decrValue);
    }

    /**
     * 删除
     * @param $keys
     * @return bool|string[]
     */
    protected function delete ($keys)
    {
        return apc_delete($keys);
    }

    /**
     * 获取过期时间
     * @param $key
     * @return int
     */
    protected function getExpire ($key)
    {
        return null;
    }
}