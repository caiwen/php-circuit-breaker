<?php
/**
 * Created by PhpStorm.
 * User: caiwen
 * Date: 2019/1/2
 * Time: 17:51
 * 熔断器接口定义
 */
namespace Globalegrow\CircuitBreaker;

interface CircuitBreakerInterface
{
    /**
     * 判断服务是否可用
     * @param $serviceName
     * @return bool|mixed
     */
    public function isAvailable($serviceName);

    /**
     * 上报服务调用失败
     * @param $serviceName
     * @return mixed
     */
    public function reportFailure($serviceName);

    /**
     * 上报服务调用成功
     * @param $serviceName
     * @return mixed
     */
    public function reportSuccess($serviceName);
}