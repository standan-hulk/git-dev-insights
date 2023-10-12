<?php

namespace GitDevInsights\FileAnalyzer\Plugins\Javascript;

use GitDevInsights\FileAnalyzer\PluginManager\FileAnalyzerPlugin;

final class JsInlineScriptTagFileAnalyzer implements FileAnalyzerPlugin
{
    public CONST SUPPORTED_FILE_EXTENSIONS = ['phtml', 'html', 'htm', 'php'];

    public static function isAllowedToScan(string $fileExt) : bool {
        return (in_array($fileExt, self::SUPPORTED_FILE_EXTENSIONS));
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

    public function canHandleFile(string $filePath): bool
    {
        // TODO: Implement canHandleFile() method.
    }

    public function analyzeFile(string $filePath)
    {
        // TODO: Implement analyzeFile() method.
    }
}