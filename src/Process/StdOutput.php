<?php

/**
 * Project: podbor-test.
 * User: marliotto
 * Date: 11.10.2021
 * Time: 23:17
 */

namespace Marliotto\PodborTest\Process;


use Marliotto\PodborTest\Output;

class StdOutput implements Output
{
    private Serializer $serializer;


    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public static function create(): self
    {
        return new self(new Serializer());
    }

    public function sendMessage($message): void
    {
        fwrite(STDOUT, $this->serializer->serialize($message) . "\n");
    }

    public function sendError($error): void
    {
        fwrite(STDERR, $this->serializer->serialize($error) . "\n");
    }
}
