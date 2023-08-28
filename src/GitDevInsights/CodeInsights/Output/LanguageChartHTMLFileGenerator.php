<?php

namespace GitDevInsights\CodeInsights\Output;

class LanguageChartHTMLFileGenerator
{
    private $jsonData;

    public function __construct(array $jsonData)
    {
        $this->jsonData = $jsonData;
    }

    public function renderChartOutput()
    {
        // Extract dates and labels
        $dates = array_keys($this->jsonData['language-global-data']);
        $labels = array_keys($this->jsonData['language-global-data'][$dates[0]]);

        // Generate datasets for each label
        $datasets = [];
        foreach ($labels as $label) {
            $data = [];
            foreach ($dates as $date) {
                $data[] = $this->jsonData['language-global-data'][$date][$label];
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Line Chart Example</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div style="width: 80vw; height: 90vh; margin: 0 auto;">
    <canvas id="myChart" width="600" height="400"></canvas>
</div>

<script>
    var labels = ' . json_encode($dates) . ';
    var datasets = ' . json_encode($datasets) . ';

    // Create the chart
    var ctx = document.getElementById("myChart").getContext("2d");
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
                        text: "Date"
                    }
                },
                y: {
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