<?php
/**
 * Created by PhpStorm.
 * User: caiwen
 * Date: 2019/1/3
 * Time: 9:53
 */

namespace Globalegrow\CircuitBreaker;


use Globalegrow\CircuitBreaker\Core\CircuitBreaker;
use Globalegrow\CircuitBreaker\Model\ConditionConfigModel;
use Globalegrow\CircuitBreaker\Storage\Adapter\ApcAdapter;
use Globalegrow\CircuitBreaker\Storage\Adapter\RedisAdapter;
use Globalegrow\CircuitBreaker\Storage\StorageInterface;
use Globalegrow\CircuitBreaker\Strategy\FailedCountStrategy;
use Globalegrow\CircuitBreaker\Strategy\FailedPercentStrategy;
use Globalegrow\CircuitBreaker\Strategy\QpsStrategy;
use Globalegrow\CircuitBreaker\Strategy\StrategyInterface;
use Predis\ClientInterface;

class Factory
{

    /**
     * 获取Apc实例
     * @param ConditionConfigModel $configModel
     * @param string $strategy
     * @return CircuitBreaker
     */
    public static function getSingleApcInstance(ConditionConfigModel $configModel,$strategy = 'qps')
    {
        $storage = new ApcAdapter();
        $strategy = self::getStrategy($storage,$configModel,$strategy);
        return new CircuitBreaker($strategy);
    }

    /**
     * 获取redis实例
     * @param ClientInterface $redis
     * @param ConditionConfigModel $configModel
     * @param string $strategy
     * @return CircuitBreaker
     */
    public static function getSingleRedisInstance(ClientInterface $redis,ConditionConfigModel $configModel,$strategy = 'qps')
    {
        $storage = new RedisAdapter($redis);
        $strategy = self::getStrategy($storage,$configModel,$strategy);
        return new CircuitBreaker($strategy);
    }

    /**
     * 获取策略
     * @param StorageInterface $storage
     * @param ConditionConfigModel $configModel
     * @param string $strategy
     * @return FailedCountStrategy|FailedPercentStrategy|QpsStrategy|string
     */
    protected static  function getStrategy(StorageInterface $storage,ConditionConfigModel $configModel,$strategy = 'qps')
    {
        switch ($strategy) {
            case StrategyInterface::QPS_TYPE:
                $strategy = new QpsStrategy($storage,$configModel);
                break;
            case StrategyInterface::FAILED_PERCENT_TYPE:
                $strategy = new FailedPercentStrategy($storage,$configModel);
                break;
            case StrategyInterface::FAILED_COUNT_TYPE:
                $strategy = new FailedCountStrategy($storage,$configModel);
                break;
            default:
                $strategy = new QpsStrategy($storage,$configModel);
        }

        return $strategy;
    }
}