<?php

namespace GitDevInsights\FileAnalyzer\Plugins\Javascript;

use GitDevInsights\FileAnalyzer\Plugins\PluginAnalysisResult;
use GitDevInsights\FileAnalyzer\Plugins\FileAnalyzerPlugin;

final class JsInlineScriptTagFileAnalyzer extends FileAnalyzerPlugin
{
    private const SUPPORTED_FILE_EXTENSIONS = ['phtml', 'html', 'htm', 'php', 'yml'];

    private const NAME = 'js-inline-script-tag';

    public function __construct()
    {
        $this->allowedExtensions = self::SUPPORTED_FILE_EXTENSIONS;
        $this->name = self::NAME;
    }

    public function analyzeFile(string $fileContent): PluginAnalysisResult
    {
        if ('' === $fileContent) {
            return $this->createEmptyAnalysisResult();
        }

        // performance boost: only continue, if a script tag is found
        if (stripos($fileContent, '<script') === false) {
            return new PluginAnalysisResult(['Inline JS' => 0]);
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

        return new PluginAnalysisResult(['Inline JS' => $totalLineCount]);
    }

    public function createEmptyAnalysisResult(): PluginAnalysisResult
    {
        return new PluginAnalysisResult(['Inline JS' => 0]);
    }
}