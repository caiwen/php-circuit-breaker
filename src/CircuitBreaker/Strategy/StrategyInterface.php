<?php
/**
 * Created by PhpStorm.
 * User: caiwen
 * Date: 2019/1/3
 * Time: 14:41
 * 策略接口定义
 */

namespace Globalegrow\CircuitBreaker\Strategy;


interface StrategyInterface
{
    /**
     * 服务调用成功类型
     */
    const RESULT_TYPE_SUCCESS_KEY="_success";
    /**
     * 服务调用失败类型
     */
    const RESULT_TYPE_FAILE_KEY="_failed";
    /**
     * 熔断标志类型
     */
    const BLOCK_KEY="_block";
    /**
     * 服务请求数类型
     */
    const RESULT_TYPE_TOTAL_KEY = "_total";
    /**
     * 以下为定义的策略类型 qps（qps维度去熔断）failedPercent（失败率去熔断）failedCount（失败数去熔断）
     */
    const QPS_TYPE = 'qps';
    const FAILED_PERCENT_TYPE = 'failedPercent';
    const FAILED_COUNT_TYPE = 'failedCount';

    /**
     * 判断服务是否可用
     * @param $serviceName
     * @return bool|mixed
     */
    public function isAvailable($serviceName);

    /**
     * 上报服务调用失败了
     * @param $serviceName
     * @return mixed
     */
    public function reportFailure($serviceName);

    /**
     * 上报服务调用成功了
     * @param $serviceName
     * @return mixed
     */
    public function reportSuccess($serviceName);
}