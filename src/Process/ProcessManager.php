<?php

/**
 * Project: podbor-test.
 * User: marliotto
 * Date: 11.10.2021
 * Time: 23:13
 */

namespace Marliotto\PodborTest\Process;

use Marliotto\PodborTest\ResultCollector;
use Symfony\Component\Process\Process;

class ProcessManager
{
    private Serializer $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public static function getCpuCores(): int
    {
        // copied from https://stackoverflow.com/a/61314463/6376828
        if (PHP_OS_FAMILY === 'Windows') {
            $cores = shell_exec('echo %NUMBER_OF_PROCESSORS%');
        } else {
            $cores = shell_exec('nproc');
        }

        $cores = (int)$cores;

        if ($cores === 0) {
            throw new \RuntimeException('Can not detect cpu cores');
        }

        return $cores;
    }

    public function run(array $tasks, ResultCollector $collector): void
    {
        $processes = [];

        foreach ($tasks as $task) {
            $process = new Process($this->createCommand($task));
            $process->start();
            $processes[] = $process;
        }

        while ($processes) {
            $processes = array_filter($processes, function (Process $currentProcess, int $taskId) use ($collector) {
                $isRunning = $currentProcess->isRunning();
                $command = $currentProcess->getCommandLine();
                if (!$isRunning) {
                    $errorOutput = $currentProcess->getErrorOutput();
                    if ($errorOutput) {
                        echo "Error when running \"{$command}\":\n    $errorOutput" . "\n";
                    }

                    $this->handleOutput($taskId, $currentProcess->getOutput(), $collector);
                }
                return $isRunning;
            }, ARRAY_FILTER_USE_BOTH);
        }
    }

    private function handleOutput(int $taskId, string $output, ResultCollector $collector): void
    {
        $lines = explode("\n", $output);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            $message = $this->parseMessage($line);
            // TODO: handle parsing error
            $collector->collect($taskId, $message['result']);
        }
    }

    private function createCommand(array $task): array
    {
        $jobFile = __DIR__ . '/../../bin/job.php';
        $taskArg = json_encode($task);

        return [
            'php',
            $jobFile,
            $taskArg,
        ];
    }

    private function parseMessage(string $line): array
    {
        return $this->serializer->unserialize($line);
    }
}
