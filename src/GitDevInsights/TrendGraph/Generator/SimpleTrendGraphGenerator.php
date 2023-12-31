<?php

namespace GitDevInsights\TrendGraph\Generator;

class SimpleTrendGraphGenerator
{
    private array $jsonData;

    private string $chartTitle;

    private string $filterKey;

    private string $chartSubtitle;

    private string $xAxisLabel;

    private string $yAxisLabel;

    public function __construct(array $jsonData, string $chartTitle, string $chartSubtitle, string $filterKey, string $xAxisLabel, string $yAxisLabel)
    {
        $this->chartTitle = $chartTitle;
        $this->chartSubtitle = $chartSubtitle;
        $this->jsonData = $jsonData;
        $this->filterKey = $filterKey;
        $this->xAxisLabel = $xAxisLabel;
        $this->yAxisLabel = $yAxisLabel;
    }

    public function renderChartOutput(): string
    {
        // Extract dates and labels
        $dates = array_keys(array_reverse($this->jsonData[$this->filterKey]));
        $labels = array_keys($this->jsonData[$this->filterKey][$dates[0]]);

        // Generate datasets for each label
        $datasets = [];
        foreach ($labels as $label) {
            $data = [];
            foreach ($dates as $date) {
                $data[] = $this->jsonData[$this->filterKey][$date][$label];
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
    <title>'.$this->chartTitle.' - code trends</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<h1>'.$this->chartTitle.'</h1>
<h2>'.$this->chartSubtitle.'</h2>
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
                        text: "'.$this->xAxisLabel.'"
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: "'.$this->yAxisLabel.'"
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

    public function writeChartOutputToFile(string $fileName) : void
    {
        $html = $this->renderChartOutput();
        file_put_contents($fileName, $html);
    }
}