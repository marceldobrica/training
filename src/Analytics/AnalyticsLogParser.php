<?php

declare(strict_types=1);

namespace App\Analytics;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class AnalyticsLogParser
{
    private string $appAnalyticsLogFile;

    public function __construct(string $appAnalyticsLogFile)
    {
        $this->appAnalyticsLogFile = $appAnalyticsLogFile;
    }

    public function getLine(): \Generator
    {
        if (!file_exists($this->appAnalyticsLogFile)) {
            throw new FileNotFoundException('Not found log file ' . $this->appAnalyticsLogFile);
        }

        $f = fopen($this->appAnalyticsLogFile, 'r');
        try {
            while (!feof($f)) {
                $line = fgets($f);
                if ($line) {
                    yield $line;
                }
            }
        } finally {
            fclose($f);
        }
    }
}
