<?php

/**
 * Project: podbor-test.
 * User: marliotto
 * Date: 12.10.2021
 * Time: 15:39
 */

namespace Marliotto\PodborTest\Process;


class Serializer
{
    public function serialize($data): string
    {
        return \serialize($data);
    }

    public function unserialize(string $serialized)
    {
        // FIXME: do not use allowed_classes = true
        return \unserialize($serialized, ['allowed_classes' => true]);
    }
}
