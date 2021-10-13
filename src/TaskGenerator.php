<?php

/**
 * Project: podbor-test.
 * User: marliotto
 * Date: 13.10.2021
 * Time: 12:15
 */

namespace Marliotto\PodborTest;


class TaskGenerator
{
    public function generate(int $number, string $filename, string $handlerClass): array
    {
        if ($number <= 0) {
            throw new \InvalidArgumentException('Task number should be 1 or more. Current value: ' . $number);
        }

        $filesize = filesize($filename);
        if (false === $filesize || 0 === $filesize) {
            throw new \RuntimeException('Can not check size if file: ' . $filename);
        }

        if ($filesize < 5000) { // do not split small files
            return [
                $this->create($filename, $handlerClass, 0),
            ];
        }

        $limit = floor($filesize / $number);
        $offset = 0;

        $tasks = [];
        for ($i = 1; $i <= $number; $i++) {
            $tasks[] = $this->create($filename, $handlerClass, $offset, $i === $number ? null : $limit);
            $offset += $limit;
        }

        return $tasks;
    }

    private function create(string $filename, string $handlerClass, int $offset, ?int $limit = null): array
    {
        return [
            'filename' => $filename,
            'handler' => $handlerClass,
            'offsetBytes' => $offset,
            'limitBytes' => $limit,
        ];
    }
}
