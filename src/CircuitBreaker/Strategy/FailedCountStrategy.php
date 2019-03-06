<?php
/**
 * Created by PhpStorm.
 * User: caiwen
 * Date: 2019/1/3
 * Time: 18:42
 * 失败总数策略
 */

namespace Globalegrow\CircuitBreaker\Strategy;


class FailedCountStrategy extends BaseStrategy
{
    /**
     * 熔断条件 （失败总数大于设定阀值）
     * @param $serviceName
     * @return bool
     */
    public function fuseCondition ($serviceName)
    {
        $curValueFaile = $this->getDataByKey($serviceName,self::RESULT_TYPE_FAILE_KEY);
        return $curValueFaile >= $this->configModel->getValue();
    }

    /**
     * 报告失败
     * @param $serviceName
     * @return mixed|void
     */
    public function reportFailure ($serviceName)
    {
        $this->report($serviceName,self::RESULT_TYPE_FAILE_KEY);
    }

    /**
     * 报告成功
     * @param $serviceName
     * @return mixed|void
     */
    public function reportSuccess ($serviceName)
    {
        $this->report($serviceName,self::RESULT_TYPE_SUCCESS_KEY);
    }

    /**
     * 降级
     * @param $serviceName
     */
    public function downgrade($serviceName)
    {
        $curlValue = $this->getDataByKey($serviceName,self::RESULT_TYPE_FAILE_KEY);
        $maxValue = $this->configModel->getValue();
        if ($curlValue > 0 && $curlValue >= $maxValue) {
            $curKey    = $serviceName.self::RESULT_TYPE_FAILE_KEY;
            $deccValue =  ($curlValue - $maxValue) + 1 ;
            $this->storage->decrement($curKey,$deccValue);
        }
    }
}