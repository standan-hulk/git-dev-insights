<?php

namespace GitDevInsights\CodeInsights\Persistence;

use Symfony\Component\Yaml\Yaml;

/**
 * @immmutable
 */
class ProjectConfigDataProvider {
    public string $repositoryUrl;
    public string $checkoutPath;
    public string $analyseResultPath;

    public string $projectName;

    public int $timeRangeWeeks;

    public function __construct(string $configFile) {
        $configData = $this->loadConfig($configFile);

        $this->repositoryUrl = $configData['project']['repository_url'] ?? '';
        $this->checkoutPath = $configData['project']['checkout_path'] ?? '';
        $this->analyseResultPath = $configData['project']['analyse_result_path'] ?? '';
        $this->projectName = $configData['project']['project_name'] ?? '';

        $this->timeRangeWeeks = (int)$configData['stats']['time_range_weeks'] ?? 10;
    }

    /**
     * @return array<string, string>
     */
    private function loadConfig(string $configFile): array {
        if (!file_exists($configFile)) {
            throw new \InvalidArgumentException("Config file not found: $configFile");
        }

        return Yaml::parseFile($configFile);
    }
}