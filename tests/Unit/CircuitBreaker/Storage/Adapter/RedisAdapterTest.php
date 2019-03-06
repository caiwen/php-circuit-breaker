<?php
/**
 * Created by PhpStorm.
 * User: caiwen
 * Date: 2019/1/4
 * Time: 14:34
 */
namespace Tests\Unit\CircuitBreaker\Storage\Adapter;
use Globalegrow\CircuitBreaker\Storage\Adapter\RedisAdapter;
use PHPUnit\Framework\TestCase;
use Predis\Client;

class RedisAdapterTest extends TestCase
{
    /**
     * @var RedisAdapter
     */
    private $_adapter;
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
        $this->_adapter = new RedisAdapter($redis);
    }

    /**
     * 测试保存
     */
    public function testSave()
    {
        $this->_adapter->saveData('aaaaa','1111',3600);
        $this->assertEquals("1111", $this->_adapter->getData('aaaaa'));
    }

    public function testIncrement()
    {
        $this->_adapter->increment('bb');
    }

    public function testLoadData()
    {
        $data = $this->_adapter->loadData([
            'aaaaa','bb'
        ]);
        print_r($data);
    }

    protected function tearDown()
    {
        $this->_adapter = null;
        parent::tearDown();
    }
}