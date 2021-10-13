<?php

/**
 * Project: podbor-test.
 * User: marliotto
 * Date: 11.10.2021
 * Time: 21:36
 */

namespace Marliotto\PodborTest\Handler;


interface Handler
{
    public function handle($data, array &$output): void;
    public function merge(array $parts);
}
