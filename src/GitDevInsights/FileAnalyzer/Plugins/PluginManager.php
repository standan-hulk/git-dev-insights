<?php

namespace GitDevInsights\FileAnalyzer\Plugins;

final class PluginManager
{
    /**
     * @var FileAnalyzerPlugin[]
     */
    private array $plugins = [];

    public function registerPlugin(FileAnalyzerPlugin $plugin): void
    {
        $this->plugins[] = $plugin;
    }

    /**
     * @param string[] $files
     * @return array<string, PluginAnalysisResult>
     */
    public function analyzeFiles(array $files) : array
    {
        $result = [];

        foreach ($files as $fileName) {
            $fileContent = file_get_contents($fileName);

            if ($fileContent === false) {
                continue;
            }

            foreach ($this->plugins as $plugin) {
                if ($plugin->canHandleFile($fileName)) {
                    $fileAnalysisResult = $plugin->analyzeFile($fileContent);

                    if (!isset($result[$plugin->name])) {
                        $result[$plugin->name] = $plugin->createEmptyAnalysisResult();
                    }

                    $result[$plugin->name]->add($fileAnalysisResult);
                }
            }
        }

        return $result;
    }
}
