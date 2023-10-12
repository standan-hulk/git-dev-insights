<?php

namespace GitDevInsights\TrendGraph\CustomStatistics;

use GitDevInsights\CodeInsights\Persistence\ProjectConfigDataProvider;
use GitDevInsights\CodeInsights\Results\AnalysisResult;
use GitDevInsights\TrendGraph\Generator\SimpleTrendGraphFilter;

class JsInlineScriptChartFileGenerator
{
    private const CHART_FILENAME = 'js-inline-script-chart.html';

    private AnalysisResult $analysisResult;

    private ProjectConfigDataProvider $projectConfigDataProvider;

    private string $filterKey = 'js-inline-script-tag';

    public function __construct(AnalysisResult $analysisResult, ProjectConfigDataProvider $projectConfigDataProvider)
    {
        $this->analysisResult = $analysisResult;
        $this->projectConfigDataProvider = $projectConfigDataProvider;
    }

    public function renderChartOutput(): string
    {
        $trendGraphFilter = new SimpleTrendGraphFilter($this->filterKey);
        $jsonData = $this->analysisResult->__toJsonData()['plugin-analysis-data'];
        $jsonData = $trendGraphFilter->filterDataByValuesSet($jsonData);

        $chartTitle = 'JS Trend Analysis';
        $chartSubtitle = 'Lines of code grouped by filetype';
        $xAxisLabel = 'Date';
        $yAxisLabel = 'Lines of code';

        // Extract dates and labels
        $dates = array_keys(array_reverse($jsonData[$this->filterKey]));
        $labels = array_keys($jsonData[$this->filterKey][$dates[0]]);

        // Generate datasets for each label
        $datasets = [];
        foreach ($labels as $label) {
            $data = [];
            foreach ($dates as $date) {
                $data[] = $jsonData[$this->filterKey][$date][$label];
            }

            $datasets[] = [
                'label' => $label,
                'data' => $data,
                'fill' => false,
                'borderColor' => 'rgb(' . rand(0, 255) . ', ' . rand(0, 255) . ', ' . rand(0, 255) . ')',
                'tension' => 0.1,
            ];
        }

        // Generate the HTML code
        $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
        h1, h2 {
            text-align: center;
        }
        h2 {
            font-size: 13px;
        }
        
        canvas {
            border: 1px solid black;
            margin: 0 auto;
            width: 80vw;
            height: 80vh;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>'.$chartTitle.' - code trends</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<h1>'.$chartTitle.'</h1>
<h2>'.$chartSubtitle.'</h2>
<div style="width: 80vw; height: 90vh; margin: 0 auto;">
    <canvas id="trend_chart" width="" height=""></canvas>
</div>

<script>
    var labels = ' . json_encode($dates, JSON_THROW_ON_ERROR) . ';
    var datasets = ' . json_encode($datasets, JSON_THROW_ON_ERROR) . ';

    // Create the chart
    var ctx = document.getElementById("trend_chart").getContext("2d");
    var myChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: labels,
            datasets: datasets,
        },
        options: {
            scales: {
                x: {
                    type: "category",
                    title: {
                        display: true,
                        text: "'.$xAxisLabel.'"
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: "'.$yAxisLabel.'"
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: "bottom"
                }
            }
        }
    });
</script>
</body>
</html>';

        return $html;
    }

    public function writeChartOutputToFile() : void
    {
        $html = $this->renderChartOutput();
        $fileName = $this->projectConfigDataProvider->analyseResultPath.'/'.self::CHART_FILENAME;
        file_put_contents($fileName, $html);
    }
}