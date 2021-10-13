<?php

/**
 * Project: podbor-test.
 * User: marliotto
 * Date: 11.10.2021
 * Time: 20:25
 */

require_once __DIR__ . '/../vendor/autoload.php';

use \Marliotto\PodborTest;

$cpuCores = PodborTest\Process\ProcessManager::getCpuCores();

$handler = new PodborTest\Handler\SumLanguageHandler();

$generator = new PodborTest\TaskGenerator();
$tasks = $generator->generate($cpuCores, __DIR__ . '/../sample.json', get_class($handler));

$processManager = new PodborTest\Process\ProcessManager(new PodborTest\Process\Serializer());

$resultCollector = new PodborTest\ResultCollector($handler);
$processManager->run($tasks, $resultCollector);

echo "============== RESULT ==============\n";
print_r($resultCollector->getResult());
