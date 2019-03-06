<?php
/**
 * Created by PhpStorm.
 * User: caiwen
 * Date: 2019/1/3
 * Time: 11:12
 * 条件配置数据模型
 */
namespace Globalegrow\CircuitBreaker\Model;
class ConditionConfigModel
{
    /**
     * 阀值
     * @var
     */
    private $value;

    /**
     * 累计周期（默认60s , 60秒）
     * @var int
     */
    private $durationSeconds = 60;

    /**
     * 回调类名字 , 不为空则回调请求:$callbackClass::$callbackFunction($key , $fuseConditonConfig , $curValue);
     * @var
     */
    private $callbackClass;

    /**
     * 指定触发条件后的回调函数名字,为空则直接抛出异常
     * @var
     */
    private $callbackFunction;

    /**
     * 当达到条件后 , 需要等待的恢复时间 , 默认为 3 (3秒)
     * @var int
     */
    private $waitingSeconds = 3;

    /**
     * 达到条件后触发回调的概率 , 默认为 1 == 100% , >= 1认为是100%触发
     * @var int
     */
    private $callbackPercent = 1;

    /**
     * @return mixed
     */
    public function getValue ()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue ($value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getDurationSeconds ()
    {
        return $this->durationSeconds;
    }

    /**
     * @param int $durationSeconds
     */
    public function setDurationSeconds ($durationSeconds)
    {
        $this->durationSeconds = $durationSeconds;
    }

    /**
     * @return mixed
     */
    public function getCallbackClass ()
    {
        return $this->callbackClass;
    }

    /**
     * @param mixed $callbackClass
     */
    public function setCallbackClass ($callbackClass)
    {
        $this->callbackClass = $callbackClass;
    }

    /**
     * @return mixed
     */
    public function getCallbackFunction ()
    {
        return $this->callbackFunction;
    }

    /**
     * @param mixed $callbackFunction
     */
    public function setCallbackFunction ($callbackFunction)
    {
        $this->callbackFunction = $callbackFunction;
    }

    /**
     * @return int
     */
    public function getWaitingSeconds ()
    {
        return $this->waitingSeconds;
    }

    /**
     * @param int $waitingSeconds
     */
    public function setWaitingSeconds ($waitingSeconds)
    {
        $this->waitingSeconds = $waitingSeconds;
    }

    /**
     * @return int
     */
    public function getCallbackPercent ()
    {
        return $this->callbackPercent;
    }

    /**
     * @param int $callbackPercent
     */
    public function setCallbackPercent ($callbackPercent)
    {
        $this->callbackPercent = $callbackPercent;
    }
}