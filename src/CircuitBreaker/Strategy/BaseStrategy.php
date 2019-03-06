<?php
/**
 * Created by PhpStorm.
 * User: caiwen
 * Date: 2019/1/3
 * Time: 14:39
 */

namespace Globalegrow\CircuitBreaker\Strategy;

use Globalegrow\CircuitBreaker\Model\CallBackEvent;
use Globalegrow\CircuitBreaker\Model\ConditionConfigModel;
use Globalegrow\CircuitBreaker\Storage\StorageInterface;

abstract class BaseStrategy implements StrategyInterface
{

    protected $configModel;

    protected $storage;

    protected $data = [];

    protected $info = [];

    public function __construct (StorageInterface $storage, ConditionConfigModel $configModel)
    {
        $this->configModel = $configModel;
        $this->storage = $storage;
    }

    /**
     * 是否放行
     * @param $serviceName
     * @return bool|mixed
     */
    public function isAvailable ($serviceName)
    {
        $blockKey = $serviceName . self::BLOCK_KEY;
        $isHit = $this->storage->getData($blockKey);
        if (!empty($isHit)) {
            return false;
        }

        if ($this->fuseCondition($serviceName)) {
            $this->storage->saveData($blockKey, 1, $this->configModel->getWaitingSeconds());
            //通知当前服务被熔断
            $this->callBack($serviceName, 1);
            $this->downgrade($serviceName);
            return false;
        }

        return true;
    }


    /**
     * 上报数据
     * @param $serviceName
     * @param $type
     * @return mixed
     */
    protected function report ($serviceName, $type)
    {
        $curKey = $serviceName . $type;
        //恢复时间
        $durationSeconds = $this->configModel->getDurationSeconds();
        try {
            //递增
            $count = $this->storage->increment($curKey);
            //初始设置需要设置有效期
            if ($count == 1) {
                return $this->storage->saveData($curKey, 1, $durationSeconds);
            }
            //查看key的过期时间
            $expireTime = $this->storage->getExpireTime($curKey);
            //过期了，删除
            if ($expireTime == -1) {
                return $this->storage->saveData($curKey, is_numeric($count) && $count > 0 ? $count : 1,
                    $durationSeconds);
            }
        } catch (\Throwable $e) {
            $this->storage->dropData($curKey);
        }
    }

    /**
     * 获取数据
     * @param $serviceName
     * @return array|mixed
     */
    protected function getData ($serviceName)
    {
        if (isset($this->data[$serviceName])) {
            return $this->data[$serviceName];
        }
        $arrKeys = array(
            $serviceName . self::BLOCK_KEY,
            $serviceName . self::RESULT_TYPE_SUCCESS_KEY,
            $serviceName . self::RESULT_TYPE_FAILE_KEY,
            $serviceName . self::RESULT_TYPE_TOTAL_KEY
        );
        $this->data[$serviceName] = $this->storage->loadData($arrKeys) ?: [];
        return $this->data[$serviceName];
    }

    /**
     * 取值
     * @param $serviceName
     * @param $type
     * @return int|mixed
     */
    protected function getDataByKey ($serviceName, $type)
    {
        $curKey = $serviceName . $type;
        if (isset($this->info[$curKey])) {
            return $this->info[$curKey];
        }
        $this->info[$curKey] = $this->storage->getData($curKey);

        return $this->info[$curKey];
    }


    /**
     * 回调通知
     * @param $serviceName
     * @param $status
     * @return mixed
     */
    protected function callBack ($serviceName, $status)
    {
        $className = $this->configModel->getCallbackClass();
        $function = $this->configModel->getCallbackFunction();
        $event = new CallBackEvent();
        $event->serviceName = $serviceName;
        //当前请求总数
        $event->curlTotal = $this->getDataByKey($serviceName, self::RESULT_TYPE_TOTAL_KEY) ?: 0;
        //当前请求成功数
        $event->curSuccessTotal = $this->getDataByKey($serviceName, self::RESULT_TYPE_SUCCESS_KEY) ?: 0;
        //当前请求失败数
        $event->curFailedTotal = $this->getDataByKey($serviceName, self::RESULT_TYPE_FAILE_KEY) ?: 0;
        $event->durationSeconds = $this->configModel->getDurationSeconds();
        $event->waitingSeconds = $this->configModel->getWaitingSeconds();
        $event->eventType = $status;
        $event->value = $this->configModel->getValue();
        if ($className) {
            return $className::$function($event);
        }
        if ($function) {
            return $function($event);
        }
    }

    //熔断条件
    abstract function fuseCondition ($serviceName);

    //上保险丝之后，下次请求允许正常，降级方案
    abstract function downgrade ($serviceName);
}