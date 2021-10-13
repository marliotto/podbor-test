<?php

/**
 * Project: podbor-test.
 * User: marliotto
 * Date: 11.10.2021
 * Time: 20:29
 */

namespace Marliotto\PodborTest;


class LineReader
{
    private string $filename;
    private int $offsetBytes;
    private ?int $limitBytes;
    private int $currentOffset = 0;

    public function __construct(string $filename, int $offsetBytes = 0, ?int $limitBytes = null)
    {
        $this->filename = $filename;
        $this->offsetBytes = $offsetBytes;
        $this->limitBytes = $limitBytes;
    }

    public function read(): iterable
    {
        $handle = fopen($this->filename, 'rb'); // FIXME: add validation, you shouldn't read any files!!!
        if (false === $handle) {
            throw new \RuntimeException('Can not read file: ' . $this->filename);
        }

        $seekResult = fseek($handle, $this->offsetBytes);
        if (-1 === $seekResult) {
            throw new \RuntimeException('Can not set offset to ' . $this->offsetBytes);
        }
        $this->currentOffset = $this->offsetBytes;

        $skipCorruptedLine = 0 !== $this->offsetBytes;
        while (($line = fgets($handle)) !== false) {
            $this->currentOffset = ftell($handle);

            if ($skipCorruptedLine) {
                $skipCorruptedLine = false;
                continue; // it's corrupted line
            }

            yield $this->currentOffset => $line;

            if ($this->isEnoughReading()) {
                break;
            }
        }

        fclose($handle);
    }

    private function isEnoughReading(): bool
    {
        if (null === $this->limitBytes) {
            return false;
        }

        $maxOffset = $this->offsetBytes + $this->limitBytes;

        return $this->currentOffset > $maxOffset;
    }
}
