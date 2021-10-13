<?php

/**
 * Project: podbor-test.
 * User: marliotto
 * Date: 12.10.2021
 * Time: 15:35
 */

namespace Marliotto\PodborTest;


interface Output
{
    public function sendMessage($message): void;
    public function sendError($error): void;
}
