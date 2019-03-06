<?php
/**
 * Created by PhpStorm.
 * User: caiwen
 * Date: 2019/1/25
 * Time: 15:42
 */
namespace Globalegrow\CircuitBreaker\Listeners;

use Globalegrow\CircuitBreaker\Model\CallBackEvent;

class CallBackListener
{
    public static function handle(CallBackEvent $event)
    {
        var_dump($event);
    }
}