<?php

namespace GitDevInsights\FileAnalyzer\Plugins\Javascript;

final class JsInlineScriptTagFileAnalyzer {
    CONST SUPPORTED_FILE_EXTENSIONS = ['phtml', 'html', 'htm', 'php'];
    private string $fileName;

    public function __construct(string $fileName) {
        $this->fileName = $fileName;
    }

    public static function isAllowedToScan(string $fileExt) : bool {
        return (in_array($fileExt, self::SUPPORTED_FILE_EXTENSIONS));
    }

    public function countInlineScriptLines(): int {
        $fileContents = file_get_contents($this->fileName);

        if (false === $fileContents) {
            return 0;
        }

        // performance boost: only continue, if a script tag is found
        if (stripos($fileContents, '<script') === false) {
            return 0;
        }

        $totalLineCount = 0;
        $pattern = '/<script[^>]*>(.*?)<\/script>/is';

        $matches = [];
        preg_match_all($pattern, $fileContents, $matches);

        if ($matches[1] !== []) {
            foreach ($matches[1] as $scriptContent) {
                $lines = preg_split("/\r\n|\r|\n/", $scriptContent);

                if($lines === false) {
                    $totalLineCount += 0;
                    continue;
                }

                $lineCount = count($lines);
                $totalLineCount += $lineCount;
            }
        }

        return $totalLineCount;
    }
}