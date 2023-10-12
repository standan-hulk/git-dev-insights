<?php


namespace GitDevInsights\CodeInsights\Service;

use GitDevInsights\CodeInsights\Persistence\ProjectConfigDataProvider;
use GitDevInsights\CodeInsights\Results\AnalysisResult;
use GitDevInsights\TrendGraph\BasicStatistics\FocusChartHTMLFileGenerator;

class GeneratorLanguageStackFocusChartService
{
    private const CHART_FILENAME = 'chart-language-stack-focus.html';

    private AnalysisResult $analysisResult;

    private ProjectConfigDataProvider $projectConfigDataProvider;

    public function __construct(AnalysisResult $analysisResult, ProjectConfigDataProvider $projectConfigDataProvider)
    {
        $this->analysisResult = $analysisResult;
        $this->projectConfigDataProvider = $projectConfigDataProvider;
    }

    public function generateOutputFile(): void
    {
        $jsonData = $this->analysisResult->__toJsonData();
        $htmlOutput = new FocusChartHTMLFileGenerator($jsonData, $this->projectConfigDataProvider->projectName);
        $htmlOutput->writeChartOutputToFile( $this->projectConfigDataProvider->analyseResultPath.'/'.self::CHART_FILENAME);
    }
}

