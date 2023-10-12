<?php

namespace GitDevInsights\FileAnalyzer\Plugins\Javascript;

use GitDevInsights\FileAnalyzer\FileAnalyzerPlugin;

final class JsInlineScriptTagFileAnalyzer extends FileAnalyzerPlugin
{
    private const SUPPORTED_FILE_EXTENSIONS = ['phtml', 'html', 'htm', 'php'];

    public function __construct()
    {
        $this->allowedExtensions = self::SUPPORTED_FILE_EXTENSIONS;
    }

    public function countInlineScriptLines(string $fileContent): int {
        if ('' === $fileContent) {
            return 0;
        }

        // performance boost: only continue, if a script tag is found
        if (stripos($fileContent, '<script') === false) {
            return 0;
        }

        $totalLineCount = 0;
        $pattern = '/<script[^>]*>(.*?)<\/script>/is';

        $matches = [];
        preg_match_all($pattern, $fileContent, $matches);

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