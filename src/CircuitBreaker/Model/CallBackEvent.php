<?php
/**
 * Created by PhpStorm.
 * User: caiwen
 * Date: 2019/1/25
 * Time: 15:12
 */

namespace Globalegrow\CircuitBreaker\Model;

namespace Globalegrow\CircuitBreaker\Model;

class CallBackEvent
{
    /**
     * 服务名称
     * @var
     */
    public $serviceName;

    /**
     * 当前请求总数
     * @var
     */
    public $curlTotal;

    /**
     * 当前请求成功总数
     * @var
     */
    public $curSuccessTotal;


    /**
     * 当前失败总数
     * @var
     */
    public $curFailedTotal;

    /**
     * 累计周期（默认60s , 60秒）
     * @var
     */
    public $durationSeconds;

    /**
     * 当达到条件后 , 需要等待的恢复时间 , 默认为 3 (3秒)
     * @var int
     */
    public $waitingSeconds;

    /**
     * 1(熔断) 2(恢复)
     * 时间类型
     * @var
     */
    public $eventType;

    /**
     * 设定阀值
     * @var
     */
    public $value;
}