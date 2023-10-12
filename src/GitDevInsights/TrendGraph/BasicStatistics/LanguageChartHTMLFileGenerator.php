<?php

namespace GitDevInsights\TrendGraph\BasicStatistics;

use GitDevInsights\CodeInsights\Persistence\ProjectConfigDataProvider;
use GitDevInsights\CodeInsights\Results\AnalysisResult;
use GitDevInsights\TrendGraph\Generator\SimpleTrendGraphFilter;
use GitDevInsights\TrendGraph\Generator\SimpleTrendGraphGenerator;

class LanguageChartHTMLFileGenerator
{

    private const CHART_FILENAME = 'chart-language.html';

    private AnalysisResult $analysisResult;

    private ProjectConfigDataProvider $projectConfigDataProvider;

    private string $filterKey = 'language-global-data';

    public function __construct(AnalysisResult $analysisResult, ProjectConfigDataProvider $projectConfigDataProvider)
    {
        $this->analysisResult = $analysisResult;
        $this->projectConfigDataProvider = $projectConfigDataProvider;
    }

    public function renderChartOutput(): string
    {
        $trendGraphFilter = new SimpleTrendGraphFilter($this->filterKey);
        $jsonData = $trendGraphFilter->filterDataByValuesSet($this->analysisResult->__toJsonData());

        $simpleTrendGraphGenerator = new SimpleTrendGraphGenerator(
            $jsonData,
            'Programming Language Trend Analysis',
            'Usage of Programming Languages by Number of Lines of Code',
            $this->filterKey,
            'Date',
            'Lines of code'
        );

        return $simpleTrendGraphGenerator->renderChartOutput();
    }

    public function writeChartOutputToFile() : void
    {
        $html = $this->renderChartOutput();
        $fileName = $this->projectConfigDataProvider->analyseResultPath.'/'.self::CHART_FILENAME;
        file_put_contents($fileName, $html);
    }
}