<?php
/**
 * Created by PhpStorm.
 * User: caiwen
 * Date: 2019/1/2
 * Time: 17:54
 * 熔断器实现
 */
namespace Globalegrow\CircuitBreaker\Core;
use Globalegrow\CircuitBreaker\CircuitBreakerInterface;
use Globalegrow\CircuitBreaker\Strategy\StrategyInterface;

class CircuitBreaker implements CircuitBreakerInterface
{
    /**
     * 熔断策略
     * @var StrategyInterface
     */
    protected $strategy;

    public function __construct (StrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * 判断服务是否可用
     * @param $serviceName
     * @return bool|mixed
     */
    public function isAvailable ($serviceName)
    {
        return $this->strategy->isAvailable($serviceName);
    }

    /**
     * 上报服务调用成功
     * @param $serviceName
     * @return mixed
     */
    public function reportSuccess ($serviceName)
    {
        return $this->strategy->reportSuccess($serviceName);
    }

    /**
     * 上报服务调用失败
     * @param $serviceName
     * @return mixed
     */
    public function reportFailure ($serviceName)
    {
        return $this->strategy->reportFailure($serviceName);
    }
}