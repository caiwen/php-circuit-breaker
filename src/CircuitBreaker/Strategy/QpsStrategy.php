<?php
/**
 * Created by PhpStorm.
 * User: caiwen
 * Date: 2019/1/3
 * Time: 17:32
 * Qps熔断策略
 */

namespace Globalegrow\CircuitBreaker\Strategy;


class QpsStrategy extends BaseStrategy
{

    /**
     * 熔断条件（当前qps大于设定qps阀值断开）
     * @param $serviceName
     * @return bool
     */
    public function fuseCondition ($serviceName)
    {
        $curlValue = $this->getDataByKey($serviceName,self::RESULT_TYPE_TOTAL_KEY);
        return  $curlValue >= $this->configModel->getValue();
    }

    /**
     * 报告失败（qps维度的只需报告请求总数）
     * @param $serviceName
     * @return mixed|void
     */
    public function reportFailure ($serviceName)
    {
        $this->report($serviceName,self::RESULT_TYPE_FAILE_KEY);
        $this->report($serviceName,self::RESULT_TYPE_TOTAL_KEY);
    }

    /**
     * 报告成功（qps维度的只需报告请求总数）
     * @param $serviceName
     * @return mixed|void
     */
    public function reportSuccess ($serviceName)
    {
        $this->report($serviceName,self::RESULT_TYPE_SUCCESS_KEY);
        $this->report($serviceName,self::RESULT_TYPE_TOTAL_KEY);
    }

    /**
     * 降级
     * @param $serviceName
     */
    public function downgrade($serviceName)
    {
        $curlValue = $this->getDataByKey($serviceName,self::RESULT_TYPE_TOTAL_KEY);
        $maxValue = $this->configModel->getValue();
        if ($curlValue > 0 && $curlValue >= $maxValue) {
            $curKey    = $serviceName.self::RESULT_TYPE_TOTAL_KEY;
            $deccValue =  ($curlValue - $maxValue) + 1 ;
            $this->storage->decrement($curKey,$deccValue);
        }
    }
}