<?php

/**
 * Project: podbor-test.
 * User: marliotto
 * Date: 11.10.2021
 * Time: 22:19
 */

namespace Marliotto\PodborTest;


use Marliotto\PodborTest\Handler\Handler;

class Job
{
    private const IN_PROGRESS_BATCH_SIZE = 1000;
    private Decoder $decoder;
    private LineReader $lineReader;
    private Handler $handler;


    public function __construct(Decoder $decoder, LineReader $lineReader, Handler $handler)
    {
        $this->decoder = $decoder;
        $this->lineReader = $lineReader;
        $this->handler = $handler;
    }

    final public static function create(array $task): self
    {
        assert(!empty($task['filename']));
        assert(!empty($task['handler']));

        return new self(
            new Decoder(),
            new LineReader($task['filename'], $task['offsetBytes'] ?? 0, $task['limitBytes'] ?? null),
            new $task['handler']
        );
    }

    public function run(Output $output): void
    {
        $result = [];
        $counter = 0;
        foreach ($this->lineReader->read() as $offset => $line) {
            try {
                $data = $this->decoder->decode($line);
                $this->handler->handle($data, $result);

                $counter++;
                if ($counter % self::IN_PROGRESS_BATCH_SIZE) {
                    $output->sendMessage(['status' => 'in-progress', 'result' => $result]);
                }
            } catch (\Throwable $e) {
                $output->sendError(['error' => $e->getTraceAsString(), 'offset' => $offset]);
            }
        }

        $output->sendMessage(['status' => 'complete', 'result' => $result]);
    }
}
