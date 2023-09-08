<?php

namespace GitDevInsights\FileTools\Service;

class JsInlineJqueryUsageFileAnalyzer {
    const SUPPORTED_FILE_EXTENSIONS = ['phtml', 'html', 'htm', 'php'];
    private string $fileName;

    public function __construct(string $fileName) {
        $this->fileName = $fileName;
    }

    public static function isAllowedToScan(string $fileExt): bool {
        return in_array($fileExt, self::SUPPORTED_FILE_EXTENSIONS);
    }

    public function countJqueryLines(): int {
        $fileContents = file_get_contents($this->fileName);

        if (false === $fileContents) {
            return 0;
        }

        $totalLineCount = 0;
        $pattern = '/<script[^>]*>(.*?)<\/script>/is';

        $matches = [];
        preg_match_all($pattern, $fileContents, $matches);

        if ($matches[1] !== []) {
            foreach ($matches[1] as $scriptContent) {
                if (strpos($scriptContent, 'jQuery') !== false) {
                    $lines = preg_split("/\r\n|\r|\n/", $scriptContent);
                    $lineCount = count($lines);
                    $totalLineCount += $lineCount;
                }
            }
        }

        return $totalLineCount;
    }
}