<?php

/**
 * Project: podbor-test.
 * User: marliotto
 * Date: 11.10.2021
 * Time: 21:40
 */

namespace Marliotto\PodborTest\Handler;


class SumLanguageHandler implements Handler
{
    private array $languagePatterns = [
        'PHP' => '/\bPHP\b/i',
        'JavaScript' => '/\bJavaScript\b/i',
        'Java' => '/\bJava\b/i',
        'Python' => '/\bPython\b/i',
    ];

    public function handle($data, array &$output): void
    {
        $description = $data['job_description'] ?? null;

        if (null === $description || '' === $description) {
            return;
        }

        foreach ($this->languagePatterns as $language => $pattern) {
            if ($this->languageExists($pattern, $description)) {
                $output[$language] = ($output[$language] ?? 0) + 1;
            }
        }
    }

    public function merge(array $parts)
    {
        $result = [];
        foreach ($parts as $part) {
            foreach ($part as $language => $sum) {
                $result[$language] = ($result[$language] ?? 0) + $sum;
            }
        }

        return $result;
    }

    private function languageExists(string $pattern, string $description): bool
    {
        return preg_match($pattern, $description) > 0;
    }
}
