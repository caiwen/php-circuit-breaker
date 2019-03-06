<?php
/**
 * Created by PhpStorm.
 * User: caiwen
 * Date: 2019/1/4
 * Time: 15:48
 */
namespace Tests\Unit\CircuitBreaker\Strategy;
use Globalegrow\CircuitBreaker\Model\ConditionConfigModel;
use Globalegrow\CircuitBreaker\Storage\Adapter\RedisAdapter;
use Globalegrow\CircuitBreaker\Strategy\FailedCountStrategy;
use Globalegrow\CircuitBreaker\Strategy\FailedPercentStrategy;
use Globalegrow\CircuitBreaker\Strategy\QpsStrategy;
use Globalegrow\CircuitBreaker\Strategy\StrategyInterface;
use PHPUnit\Framework\TestCase;
use Predis\Client;

class QpsStrategyTest extends TestCase
{
    /**
     * @var StrategyInterface $strategy
     */
    protected $strategy;

    protected function setUp()
    {
        parent::setUp();
        $redis = new Client([
            'tcp://127.0.0.1:26395',
            'tcp://127.0.0.1:26397',
            'tcp://127.0.0.1:26396'
        ],[
            'timeout' => 10,
            'replication' =>'sentinel',
            'service' => 'sentinel-127.0.0.1-26393',
            'prefix' => 'app:',
            'parameters' => [
                'database'=>0
            ]
        ]);
        $storage = new RedisAdapter($redis);
        $configModel = new ConditionConfigModel();
        $configModel->setValue(2);
       // $configModel->setValue(0.5);
        $configModel->setDurationSeconds(3600);
        $configModel->setWaitingSeconds(300);
        $configModel->setCallbackClass("Globalegrow\CircuitBreaker\Listeners\CallBackListener");
        $configModel->setCallbackFunction("handle");

        $this->strategy = new QpsStrategy($storage,$configModel);
        //$this->strategy = new FailedCountStrategy($storage,$configModel);
        //$this->strategy = new FailedPercentStrategy($storage,$configModel);
    }

    public function testReportSuccess()
    {
        $this->strategy->reportSuccess('test');
    }

    public function testReportFailed()
    {
        $this->strategy->reportFailure('test');
    }

    public function testIsAvailable()
    {
        $ret = $this->strategy->isAvailable('test');
        var_dump($ret);
    }
}