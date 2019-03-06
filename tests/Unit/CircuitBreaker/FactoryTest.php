<?php
/**
 * Created by PhpStorm.
 * User: caiwen
 * Date: 2019/1/4
 * Time: 10:35
 */
namespace Tests\Unit\CircuitBreaker;
use Globalegrow\CircuitBreaker\CircuitBreakerInterface;
use Globalegrow\CircuitBreaker\Factory;
use Globalegrow\CircuitBreaker\Model\ConditionConfigModel;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testGetSingleApcInstance()
    {
        $configModel = new ConditionConfigModel();
        $configModel->setValue(1);
        $configModel->setDurationSeconds(1);
        $configModel->setWaitingSeconds(5);
        $serverName = 'test';
        $cb = Factory::getSingleApcInstance($configModel);
        $this->assertTrue($cb instanceof CircuitBreakerInterface);
        $this->assertEquals(true, $cb->isAvailable($serverName));
    }
}