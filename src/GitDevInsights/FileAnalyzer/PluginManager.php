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

    /**
     * @param string[] $files
     */
    public function analyzeFiles(array $files) : void
    {
        foreach ($files as $fileName) {
            $fileContent = file_get_contents($fileName);

            foreach ($this->plugins as $plugin) {
                if ($plugin->canHandleFile($fileName)) {
                    dump($plugin);die;
                    $jsonResult = $plugin->analyzeFile($fileContent);
                    dump($jsonResult);
                }
            }

            dump('ende');die;
        }
    }
}
