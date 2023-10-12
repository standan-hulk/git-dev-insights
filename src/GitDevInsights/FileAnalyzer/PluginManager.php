<?php

namespace GitDevInsights\FileAnalyzer;

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

    public function getPlugins(): array
    {
        return $this->plugins;
    }

    public function analyzeFilesWithSearchPattern(string $pattern) : void
    {
        $files = glob($pattern);

        foreach ($files as $fileName) {
            $fileContent = file_get_contents($fileName);

            foreach ($this->plugins as $plugin) {
                if ($plugin->canHandleFile($fileName)) {
                    $jsonResult = $plugin->analyzeFile($fileContent);
                    dump($jsonResult);
                }
            }
        }
    }
}
