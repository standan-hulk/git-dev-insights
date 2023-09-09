<?php

namespace GitDevInsights\CodeInsights\Output;

class FocusChartHTMLFileGenerator
{
    private array $jsonData;

    private string $chartTitle;

    public function __construct(array $jsonData, string $chartTitle)
    {
        $this->jsonData = $jsonData;
        $this->chartTitle = $chartTitle;
        $this->jsonData['language-focus-data'] = $this->filterDataByValuesSet();
    }

    private function getValuesSetForOutput(): array {
        $dates = $this->jsonData['language-focus-data'];

        $valuesSet = [];

        if ($dates !== []) {
            foreach ($dates as $values) {
                foreach ($values as $key => $value) {
                    if ((int)$value > 0) {
                        $valuesSet[$key] = 1;
                    }
                }
            }
        }
        return $valuesSet;
    }

    private function filterDataByValuesSet(): array {
        $filteredData = [];

        $valuesSet = $this->getValuesSetForOutput();

        foreach ($this->jsonData['language-focus-data'] as $date => $values) {
            $filteredValues = [];

            foreach ($values as $key => $value) {
                if (isset($valuesSet[$key]) && (int)$value > 0) {
                    $filteredValues[$key] = $value;
                }
            }

            if (!empty($filteredValues)) {
                $filteredData[$date] = $filteredValues;
            }
        }

        return $filteredData;
    }

    public function renderChartOutput(): string
    {
        $dates = array_keys(array_reverse($this->jsonData['language-focus-data']));
        $labels = array_keys($this->jsonData['language-focus-data'][$dates[0]]);

        // Generate datasets for each label
        $datasets = [];
        foreach ($labels as $label) {

            $data = [];

            foreach ($dates as $date) {
                if(isset($this->jsonData['language-focus-data'][$date][$label])) {
                    $data[] = $this->jsonData['language-focus-data'][$date][$label];
                } else {
                    $data[] = 0;
                }
            }

            $datasets[] = [
                'label' => $label,
                'data' => $data,
                'backgroundColor' => 'rgba(' . rand(0, 255) . ', ' . rand(0, 255) . ', ' . rand(0, 255) . ', 0.5)',
                'borderColor' => 'rgb(' . rand(0, 255) . ', ' . rand(0, 255) . ', ' . rand(0, 255) . ')',
                'borderWidth' => 1,
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
<h1>'.$this->chartTitle.' - Programming Language Trend Analysis</h1>
<h2>Backend / Frontend usage bye programming languages (Lines of Code)</h2>
<div style="width: 80vw; height: 90vh; margin: 0 auto;">
    <canvas id="trend_chart" width="" height=""></canvas>
</div>

<script>
    var labels = ' . json_encode($dates) . ';
    var datasets = ' . json_encode($datasets) . ';

    // Create the chart
    var ctx = document.getElementById("trend_chart").getContext("2d");
    var myChart = new Chart(ctx, {
        type: "bar",
        data: {
            labels: labels,
            datasets: datasets,
        },
        options: {
            scales: {
                x: {
                    stacked: true, // Enable stacking for X-axis
                    title: {
                        display: true,
                        text: "Date"
                    }
                },
                y: {
                    stacked: true, // Enable stacking for Y-axis
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: "Number of Lines of Code"
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

    public function writeChartOutputToFile($fileName) : void
    {
        $html = $this->renderChartOutput();
        file_put_contents($fileName, $html);
    }
}
