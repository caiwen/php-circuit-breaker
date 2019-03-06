<?php
/**
 * Created by PhpStorm.
 * User: caiwen
 * Date: 2019/1/2
 * Time: 17:18
 * 存储接口
 */
namespace Globalegrow\CircuitBreaker\Storage;

interface StorageInterface
{

    /**
     * 获取数据
     * @param $key
     * @return mixed
     */
    public function getData($key);

    /**
     * 载入数据
     * @param $keyList
     * @return mixed
     */
    public function loadData($keyList);

    /**
     * 保存数据
     * @param $key
     * @param $value
     * @param $ttl
     * @return mixed
     */
    public function saveData($key,$value,$ttl = 3600);

    /**
     * 递增
     * @param $key
     * @param int $incValue
     * @return mixed
     */
    public function increment($key, $incValue = 1);

    /**
     * 递减
     * @param $key
     * @param int $incValue
     * @return mixed
     */
    public function decrement($key, $incValue = 1);

    /**
     * 删除数据
     * @param $key
     * @return mixed
     */
    public function dropData($key);

    /**
     * 获取过期时间
     * @param $key
     * @return mixed
     */
    public function getExpireTime($key);
}