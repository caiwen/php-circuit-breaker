<?php
/**
 * Created by PhpStorm.
 * User: caiwen
 * Date: 2019/1/3
 * Time: 16:03
 * 失败率策略
 */

namespace Globalegrow\CircuitBreaker\Strategy;


class FailedPercentStrategy extends BaseStrategy
{

    /**
     * 熔断条件 （失败了大于设定阀值）
     * @param $serviceName
     * @return bool
     */
    public function fuseCondition ($serviceName)
    {
        $curValueSuccess = $this->getDataByKey($serviceName,self::RESULT_TYPE_SUCCESS_KEY);
        $curValueFaile = $this->getDataByKey($serviceName,self::RESULT_TYPE_FAILE_KEY);
        $curPercent = $curValueFaile / ($curValueSuccess + $curValueFaile);

        return $curPercent >= $this->configModel->getValue();
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
        $curValueSuccess = $this->getDataByKey($serviceName,self::RESULT_TYPE_SUCCESS_KEY);
        $curValueFaile = $this->getDataByKey($serviceName,self::RESULT_TYPE_FAILE_KEY);
        $curPercent = $curValueFaile / ($curValueSuccess + $curValueFaile);
        $maxValue = $this->configModel->getValue();
        //当前失败率大于设定阀值
        if ($curPercent >= $maxValue) {
            $curFailedKey    = $serviceName.self::RESULT_TYPE_FAILE_KEY;
            $curSuccessKey = $serviceName.self::RESULT_TYPE_SUCCESS_KEY;
            //失败数减1
            $this->storage->decrement($curFailedKey,1);
            //成功数加1
            $this->storage->increment($curSuccessKey,1);
        }
    }
}