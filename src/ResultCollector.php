<?php

/**
 * Project: podbor-test.
 * User: marliotto
 * Date: 13.10.2021
 * Time: 12:24
 */

namespace Marliotto\PodborTest;


use Marliotto\PodborTest\Handler\Handler;

class ResultCollector
{
    private Handler $handler;
    private array $results = [];

    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    public function collect(int $taskId, array $result): void
    {
        $this->results[$taskId] = $result;
    }

    public function getResult()
    {
        return $this->handler->merge($this->results);
    }
}
