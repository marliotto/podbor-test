<?php

/**
 * Project: podbor-test.
 * User: marliotto
 * Date: 13.10.2021
 * Time: 11:38
 */

require_once __DIR__ . '/../vendor/autoload.php';

// TODO: json_encode uses in ProcessManager, extract to one place
$task = \json_decode($_SERVER['argv'][1], true, 512, JSON_THROW_ON_ERROR);

$output = \Marliotto\PodborTest\Process\StdOutput::create();
$job = \Marliotto\PodborTest\Job::create($task);

$job->run($output);
